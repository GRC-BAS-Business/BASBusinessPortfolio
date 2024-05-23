<?php
/**
 *  This is the Timeline class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Timeline
{
    private DateTime $_createdDate;
    private int $_itemID;
    private int $_taskID;
    private int $_timelineID;
    private string $_title;

    /**
     * Constructs a Timeline object.
     *
     * @param DateTime $createdDate The created date for the object.
     * @param int $itemID The item ID for the object.
     * @param int $taskID The task ID for the object.
     * @param int $timelineID The timeline ID for the object.
     * @param string $title The title of the object.
     *
     * @return void
     */
    public function __construct(DateTime $createdDate, int $itemID, int $taskID, int $timelineID, string $title)
    {
        $this->_createdDate = $createdDate;
        $this->_itemID = $itemID;
        $this->_taskID = $taskID;
        $this->_timelineID = $timelineID;
        $this->_title = $title;
    }

    /**
     * Gets the created date of the object.
     *
     * @return DateTime The created date.
     */
    public function getCreatedDate(): DateTime
    {
        return $this->_createdDate;
    }

    /**
     * Sets the created date of the object.
     *
     * @param DateTime $createdDate The created date to be set.
     *
     * @return void
     */
    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->_createdDate = $createdDate;
    }

    /**
     * Returns the ID of the item.
     *
     * @return int The ID of the item.
     */
    public function getItemID(): int
    {
        return $this->_itemID;
    }

    /**
     * Sets the item ID of the object.
     *
     * @param int $itemID The item ID to be set.
     *
     * @return void
     */
    public function setItemID(int $itemID): void
    {
        $this->_itemID = $itemID;
    }

    /**
     * Returns the task ID of the object.
     *
     * @return int The task ID.
     */
    public function getTaskID(): int
    {
        return $this->_taskID;
    }

    /**
     * Sets the task ID of the object.
     *
     * @param int $taskID The task ID to be set.
     *
     * @return void
     */
    public function setTaskID(int $taskID): void
    {
        $this->_taskID = $taskID;
    }

    /**
     * Retrieves the timeline ID of the object.
     *
     * @return int The timeline ID.
     */
    public function getTimelineID(): int
    {
        return $this->_timelineID;
    }

    /**
     * Sets the timeline ID of the object.
     *
     * @param int $timelineID The timeline ID to be set.
     *
     * @return void
     */
    public function setTimelineID(int $timelineID): void
    {
        $this->_timelineID = $timelineID;
    }

    /**
     * Retrieves the title of the object.
     *
     * @return string The title of the object.
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * Sets the title of the object.
     *
     * @param string $title The title to be set.
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->_title = $title;
    }
}