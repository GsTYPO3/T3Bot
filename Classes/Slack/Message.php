<?php
/**
 * T3Bot.
 *
 * @author Frank Nägler <typo3@naegler.net>
 *
 * @link http://www.t3bot.de
 * @link http://wiki.typo3.org/T3Bot
 */
namespace T3Bot\Slack;

use T3Bot\Slack\Message\Attachment;

/**
 * Class Message.
 */
class Message
{
    /**
     * The text so be send.
     *
     * @var string
     */
    protected $text;

    /**
     * The URL for the avatar image.
     *
     * @var string
     */
    protected $icon_emoji;

    /**
     * @var array<T3Bot\Slack\Message\Attachment>
     */
    protected $attachments = array();

    /**
     * Constructor for a message.
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $property => $value) {
                if (property_exists($this, $property) && !empty($this->$property)) {
                    $this->$property = $value;
                }
            }
        }
        if (empty($this->icon_emoji)) {
            $this->icon_emoji = $GLOBALS['config']['slack']['botAvatar'];
        }
    }

    /**
     * @return string
     */
    public function getJSON()
    {
        $result = new \stdClass();
        $properties = get_class_vars(get_class($this));
        foreach ($properties as $property => $value) {
            if ($property === 'attachments') {
                if (!empty($this->attachments)) {
                    $result->attachments = array();
                    /** @var Attachment $attachment */
                    foreach ($this->attachments as $attachment) {
                        $result->attachments[] = $attachment->asStdClass();
                    }
                }
            } else {
                if (!empty($this->$property)) {
                    $result->$property = $this->$property;
                }
            }
        }

        return json_encode($result);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getIconEmoji()
    {
        return $this->icon_emoji;
    }

    /**
     * @param string $icon_emoji
     */
    public function setIconEmoji($icon_emoji)
    {
        $this->icon_emoji = $icon_emoji;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @param Attachment $attachment
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;
    }
}
