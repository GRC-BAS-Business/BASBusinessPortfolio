<?php
/**
 *  This is the Item class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Item
{
    private DateTime $_creationDate;
    private string $_itemDescription;
    private int $_itemID;
    private string $_itemType;
    private string $_title;

    /**
     * Constructs an Item object
     *
     * @param DateTime $_creationDate The creation date of the item.
     * @param string $_itemDescription The description of the item.
     * @param int $_itemID The ID of the item.
     * @param string $_itemType The type of the item.
     * @param string $_title The title of the item.
     * @return void
     */
    public function __construct(DateTime $_creationDate, string $_itemDescription, int $_itemID, string $_itemType, string $_title)
    {
        $this->_creationDate = $_creationDate;
        $this->_itemDescription = $_itemDescription;
        $this->_itemID = $_itemID;
        $this->_itemType = $_itemType;
        $this->_title = $_title;
    }

    /**
     * Returns the creation date of the item.
     *
     * @return DateTime The creation date of the item.
     */
    public function getCreationDate(): DateTime
    {
        return $this->_creationDate;
    }

    /**
     * Sets the creation date of the object.
     *
     * @param DateTime $creationDate The creation date to set.
     *
     * @return void
     */
    public function setCreationDate(DateTime $creationDate): void
    {
        $this->_creationDate = $creationDate;
    }

    /**
     * Returns the item description.
     *
     * @return string The item description.
     */
    public function getItemDescription(): string
    {
        return $this->_itemDescription;
    }

    /**
     * Sets the item description.
     *
     * @param string $itemDescription The description of the item.
     *
     * @return void
     */
    public function setItemDescription(string $itemDescription): void
    {
        $this->_itemDescription = $itemDescription;
    }

    /**
     * Retrieves the ID of the item.
     *
     * @return int The ID of the item.
     */
    public function getItemID(): int
    {
        return $this->_itemID;
    }

    /**
     * Sets the item ID.
     *
     * @param int $itemID The new item ID to be set.
     * @return void
     */
    public function setItemID(int $itemID): void
    {
        $this->_itemID = $itemID;
    }

    /**
     * Gets the item type.
     *
     * @return string The item type.
     */
    public function getItemType(): string
    {
        return $this->_itemType;
    }

    /**
     * Sets the item type.
     *
     * @param string $itemType The new item type to be set.
     * @return void
     */
    public function setItemType(string $itemType): void
    {
        $this->_itemType = $itemType;
    }

    /**
     * Gets the title of the item.
     *
     * @return string The title of the item.
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * Sets the title.
     *
     * @param string $title The new title to be set.
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->_title = $title;
    }

    public static function getItems(): array
    {
        // Get the database connection
        $dbh = Database::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM PortfolioItem";

        // Execute the statement
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        // Fetch all results
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Create an array to hold the Item objects
        $items = [];

        foreach($rows as $row) {
            // Create a new Item object for each row
            $item = new Item(
                new DateTime($row['creationDate']),
                $row['itemDescription'],
                $row['itemID'],
                $row['itemType'],
                $row['title']
            );

            // Add the Item object to the items array
            $items[] = $item;
        }

        return $items;
    }

    public function saveItem(): bool
    {
        // Get the database connection
        $dbh = Database::getConnection();

        $sql = "INSERT INTO `PortfolioItem` ( `ItemID`, `Title`, `CreationDate`, 
            `ItemType`, `ItemDescription`) VALUES (:itemID, :itemTitle, :creationDate, :itemType, :itemDescription)";

        // Prepare the statement
        $stmt = $dbh->prepare($sql);

        // Bind the parameters
        $stmt->bindValue(':itemID', $this->_itemID);
        $stmt->bindValue(':itemTitle', $this->_title);
        $stmt->bindValue(':creationDate', $this->_creationDate->format('Y-m-d H:i:s'));
        $stmt->bindValue(':itemType', $this->_itemType);
        $stmt->bindValue(':itemDescription', $this->_itemDescription);
        // Execute the statement
        $stmt->execute();

        return $stmt->rowCount() === 1;
    }

}