<?php

/* mail/reward.twig */
class __TwigTemplate_677332c51ad1733fdf51b057286553f5a30c949f635fdc537adac0fb9fae52a2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo (isset($context["text_received"]) ? $context["text_received"] : null);
        echo "

";
        // line 3
        echo (isset($context["text_total"]) ? $context["text_total"] : null);
    }

    public function getTemplateName()
    {
        return "mail/reward.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 3,  19 => 1,);
    }
}
/* {{ text_received }}*/
/* */
/* {{ text_total }}*/
