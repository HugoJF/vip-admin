<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function scrollToView(Browser $browser, $element)
    {
        // $browser->script("document.querySelector('" . $this->elements()[ $element ] . "').scrollIntoView()");
        $browser->script("function scrollToMiddle(id) {var elem_position = $(id).offset().top;var window_height = $(window).height();var y = elem_position - window_height/2;window.scrollTo(0,y);}scrollToMiddle('".$this->elements()[$element]."');");
    }

    public function scrollToViewAndClick(Browser $browser, $element)
    {
        // $browser->script("document.querySelector('" . $this->elements()[ $element ] . "').scrollIntoView()");
        $this->scrollToView($browser, $element);
        $browser->click($this->elements()[$element]);
    }

    public function scrollToViewAndCheck(Browser $browser, $element)
    {
        $this->scrollToView($browser, $element);
        $browser->check($this->elements()[$element]);
    }
}
