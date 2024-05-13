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
    public function __construct(object $f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Renders the home view.
     *
     * @return void
     */
    function renderHome(): void
    {
        // TODO check if user is stored in session, if not default to login page
        $view = new Template();
        echo $view->render('app/view/home.html');
    }

    /**
     * Renders the login view.
     *
     * @return void
     */
    function renderLogin(): void
    {
        $view = new Template();
        echo $view->render('app/view/login.html');
    }

    /**
     * Renders the portfolio_timeline view.
     *
     * @return void
     */
    function renderTimeline(): void
    {
        $view = new Template();
        echo $view->render('app/view/timeline.html');
    }
}