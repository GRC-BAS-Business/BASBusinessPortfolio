<?php
/**
 *  This is the Task class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Task
{
    private bool|int $_completionStatus;
    private DateTime $_dueDate;
    private int $_itemID;
    private DateTime $_startDate;
    private string $_taskDescription;
    private int $_taskID;
    private string $_title;

    /**
     * Constructs a Task object.
     *
     * @param bool|int $_completionStatus The completion status of the task. Must be a boolean or integer value.
     * @param DateTime $_dueDate The due date of the task.
     * @param int $_itemID The ID of the item associated with the task.
     * @param DateTime $_startDate The start date of the task.
     * @param string $_taskDescription The description of the task.
     * @param int $_taskID The ID of the task.
     * @param string $_title The title of the task.
     */
    public function __construct(bool|int $_completionStatus, DateTime $_dueDate, int $_itemID, DateTime $_startDate, string $_taskDescription, int $_taskID, string $_title)
    {
        $this->_completionStatus = $_completionStatus;
        $this->_dueDate = $_dueDate;
        $this->_itemID = $_itemID;
        $this->_startDate = $_startDate;
        $this->_taskDescription = $_taskDescription;
        $this->_taskID = $_taskID;
        $this->_title = $_title;
    }

    /**
     * Retrieves the completion status of the task.
     *
     * @return bool|int The completion status of the task. Must be a boolean or integer value.
     */
    public function getCompletionStatus(): bool|int
    {
        return $this->_completionStatus;
    }

    /**
     * Set the completion status of a task.
     *
     * @param bool|int $completionStatus The completion status of the task. Can be a boolean or an integer.
     * @return void
     */
    public function setCompletionStatus(bool|int $completionStatus): void
    {
        $this->_completionStatus = $completionStatus;
    }

    /**
     * Get the due date of a task.
     *
     * @return DateTime The due date of the task as a DateTime object.
     */
    public function getDueDate(): DateTime
    {
        return $this->_dueDate;
    }

    /**
     * Set the due date of a task.
     *
     * @param DateTime $dueDate The due date of the task. Should be an instance of DateTime class.
     * @return void
     */
    public function setDueDate(DateTime $dueDate): void
    {
        $this->_dueDate = $dueDate;
    }

    /**
     * Get the ID of the item.
     *
     * @return int The ID of the item.
     */
    public function getItemID(): int
    {
        return $this->_itemID;
    }

    /**
     * Set the ID of an item.
     *
     * @param int $itemID The ID of the item.
     * @return void
     */
    public function setItemID(int $itemID): void
    {
        $this->_itemID = $itemID;
    }

    /**
     * Get the start date of a task.
     *
     * @return DateTime The start date of the task.
     */
    public function getStartDate(): DateTime
    {
        return $this->_startDate;
    }

    /**
     * Set the start date of a task.
     *
     * @param DateTime $startDate The start date of the task as a DateTime object.
     * @return void
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->_startDate = $startDate;
    }

    /**
     * Get the description of a task.
     *
     * @return string The description of the task.
     */
    public function getTaskDescription(): string
    {
        return $this->_taskDescription;
    }

    /**
     * Set the description of a task.
     *
     * @param string $taskDescription The description of the task.
     * @return void
     */
    public function setTaskDescription(string $taskDescription): void
    {
        $this->_taskDescription = $taskDescription;
    }

    /**
     * Get the ID of a task.
     *
     * @return int The ID of the task as an integer.
     */
    public function getTaskID(): int
    {
        return $this->_taskID;
    }

    /**
     * Set the task ID of a task.
     *
     * @param int $taskID The ID of the task to be set.
     * @return void
     */
    public function setTaskID(int $taskID): void
    {
        $this->_taskID = $taskID;
    }

    /**
     * Get the title of a task.
     *
     * @return string The title of the task.
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * Set the title of a task.
     *
     * @param string $title The title of the task.
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->_title = $title;
    }
}