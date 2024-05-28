<?php
/**
 *  This is the Image class for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Image
{
    private int $_imageID; //ImageID
    private string $_ImagePath; // ImagePath

    /**
     * @param int $_imageID
     * @param string $_ImagePath
     */
    public function __construct(int $_imageID, string $_ImagePath)
    {
        $this->_imageID = $_imageID;
        $this->_ImagePath = $_ImagePath;
    }

    public function getImageID(): int
    {
        return $this->_imageID;
    }

    public function setImageID(int $imageID): void
    {
        $this->_imageID = $imageID;
    }

    public function getImagePath(): string
    {
        return $this->_ImagePath;
    }

    public function setImagePath(string $ImagePath): void
    {
        $this->_ImagePath = $ImagePath;
    }

    public function uploadImage()
    {
        // During file upload...
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/../bas-portfolio-db-config.php';
        $uploadFile = $uploadDir . basename($_FILES['userfile']['name']);

        if (move_uploaded_file($_FILES['user_file']['tmp_name'], $uploadFile)) {
            echo "File is valid, and was successfully uploaded.\n";
            // Now save the path in the database
            $sql = "INSERT INTO `Image` SET `ImagePath` = :imagePath";
            $stmt = Database::getConnection()->prepare($sql);
            $stmt->bindValue(':imagePath', $uploadFile);
            $stmt->execute();
        } else {
            echo "Possible file upload attack!\n";
        }
    }

    public function getItemImage()
    { 
        // During file retrieval...
        $sql = "SELECT `ImagePath` FROM `Image` WHERE `ImageId` = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $this->_imageID);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagePath = $row['ImagePath'];
    }
}