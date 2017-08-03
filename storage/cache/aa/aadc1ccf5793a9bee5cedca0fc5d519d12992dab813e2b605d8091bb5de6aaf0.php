<?php

/* default/template/mail/order_edit.twig */
class __TwigTemplate_09b207de8f12ab3055cf1a9e7df4f57ccb782b5b24d28e4d10e00186204ced3f extends Twig_Template
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
        echo (isset($context["text_order_id"]) ? $context["text_order_id"] : null);
        echo " ";
        echo (isset($context["order_id"]) ? $context["order_id"] : null);
        echo "
";
        // line 2
        echo (isset($context["text_date_added"]) ? $context["text_date_added"] : null);
        echo " ";
        echo (isset($context["date_added"]) ? $context["date_added"] : null);
        echo "

";
        // line 4
        echo (isset($context["text_order_status"]) ? $context["text_order_status"] : null);
        echo "

";
        // line 6
        echo (isset($context["order_status"]) ? $context["order_status"] : null);
        echo "

";
        // line 8
        if ((isset($context["link"]) ? $context["link"] : null)) {
            // line 9
            echo (isset($context["text_link"]) ? $context["text_link"] : null);
            echo "

";
            // line 11
            echo (isset($context["link"]) ? $context["link"] : null);
            echo "
";
        }
        // line 13
        if ((isset($context["comment"]) ? $context["comment"] : null)) {
            // line 14
            echo "
";
            // line 15
            echo (isset($context["text_comment"]) ? $context["text_comment"] : null);
            echo "

";
            // line 17
            echo (isset($context["comment"]) ? $context["comment"] : null);
            echo "
";
            // line 18
            echo (isset($context["text_footer"]) ? $context["text_footer"] : null);
            echo "
";
        } else {
            // line 19
            echo " 
";
            // line 20
            echo (isset($context["text_footer"]) ? $context["text_footer"] : null);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "default/template/mail/order_edit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 20,  73 => 19,  68 => 18,  64 => 17,  59 => 15,  56 => 14,  54 => 13,  49 => 11,  44 => 9,  42 => 8,  37 => 6,  32 => 4,  25 => 2,  19 => 1,);
    }
}
/* {{ text_order_id }} {{ order_id }}*/
/* {{ text_date_added }} {{ date_added }}*/
/* */
/* {{ text_order_status }}*/
/* */
/* {{ order_status }}*/
/* */
/* {% if link %}*/
/* {{ text_link }}*/
/* */
/* {{ link }}*/
/* {% endif %}*/
/* {% if comment %}*/
/* */
/* {{ text_comment }}*/
/* */
/* {{ comment }}*/
/* {{ text_footer }}*/
/* {% else %} */
/* {{ text_footer }}*/
/* {% endif %}*/
