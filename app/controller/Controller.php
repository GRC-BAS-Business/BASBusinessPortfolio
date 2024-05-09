<?php
/**
 *  This is the CONTROLLER for BAS Business Portfolio application
 *
 *  @authors Braedon Billingsley, Will
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
class Controller
{
    private object $_f3;

    /**
     * Constructs a controller object.
     *
     * @param mixed $f3 The global $f3 hive to be assigned to the $_f3 parameter.
     * @return void
     */
    public function __construct(mixed $f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Renders the home HTML page.
     *
     * This method creates a new instance of the Template class and uses it to render the home.html page.
     * The rendered HTML is then echoed to the output.
     *
     * @return void
     */
    function renderHome(): void
    {
        $view = new Template();
        echo $view->render('app/view/home.html');
    }
}