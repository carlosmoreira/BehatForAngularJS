<?php

namespace Mip\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class RestrictedAccessContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /** @BeforeStep @javascript */
    public function beforeStep($event)
    {
        $waitTime = 10000;
        $jqDefined = "return (typeof jQuery != 'undefined')";
        $active = "(0 === jQuery.active && 0 === jQuery('animated').length)";
        if ($this->getSession()->evaluateScript($jqDefined)) {
            $this->getSession()->wait($waitTime, $active);
        }
    }


    /**
     * @When I wait for ajax
     */
    public function iWaitForAjax()
{
    $waitTime = 10000;
    try {
        //Wait for Angular

        $angularIsNotUndefined = $this->getSession()->evaluateScript("return (typeof angular != 'undefined')");
        if ($angularIsNotUndefined) {
            //If you run the below code on a page ending in #, the page reloads.
            if (substr($this->getSession()->getCurrentUrl(), -1) !== '#') {
                $angular = 'angular.getTestability(document.body).whenStable(function() {
                window.__testable = true;
            })';
                $this->getSession()->evaluateScript($angular);
                $this->getSession()->wait($waitTime, 'window.__testable == true');
            }
  
            /*
             * Angular JS AJAX can't be detected overall like in jQuery,
             * but we can check if any of the html elements are marked as showing up when ajax is running,
             * then wait for them to disappear.
             */
            $ajaxRunningXPath = "//*[@ng-if='ajax_running']";
            //$this->waitForElementToDisappear($ajaxRunningXPath, $waitTime);
        }
  
        //Wait for jQuery
        if ($this->getSession()->evaluateScript("return (typeof jQuery != 'undefined')")) {
            $this->getSession()->wait($waitTime, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
        }
    } catch (Exception $e) {
        //var_dump($e->getMessage()); //Debug here.
    }
}
}
