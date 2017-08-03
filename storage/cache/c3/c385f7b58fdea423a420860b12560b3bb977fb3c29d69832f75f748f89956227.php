<?php

/* default/template/account/tracking.twig */
class __TwigTemplate_4f4ec4eb9b00e54285fb8360822171185ee975b3a46d7cb4c5295f0c0dd7da84 extends Twig_Template
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
        echo (isset($context["header"]) ? $context["header"] : null);
        echo "
<div id=\"account-tracking\" class=\"container\">
  <ul class=\"breadcrumb\">
    ";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 5
            echo "    <li><a href=\"";
            echo $this->getAttribute($context["breadcrumb"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["breadcrumb"], "text", array());
            echo "</a></li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "  </ul>
  <div class=\"row\">";
        // line 8
        echo (isset($context["column_left"]) ? $context["column_left"] : null);
        echo "
    ";
        // line 9
        if (((isset($context["column_left"]) ? $context["column_left"] : null) && (isset($context["column_right"]) ? $context["column_right"] : null))) {
            // line 10
            echo "    ";
            $context["class"] = "col-sm-6";
            // line 11
            echo "    ";
        } elseif (((isset($context["column_left"]) ? $context["column_left"] : null) || (isset($context["column_right"]) ? $context["column_right"] : null))) {
            // line 12
            echo "    ";
            $context["class"] = "col-sm-9";
            // line 13
            echo "    ";
        } else {
            // line 14
            echo "    ";
            $context["class"] = "col-sm-12";
            // line 15
            echo "    ";
        }
        // line 16
        echo "    <div id=\"content\" class=\"";
        echo (isset($context["class"]) ? $context["class"] : null);
        echo "\">";
        echo (isset($context["content_top"]) ? $context["content_top"] : null);
        echo "
      <h1>";
        // line 17
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h1>
      <p>";
        // line 18
        echo (isset($context["text_description"]) ? $context["text_description"] : null);
        echo "</p>
      <form class=\"form-horizontal\">
        <div class=\"form-group\">
          <label class=\"col-sm-2 control-label\" for=\"input-code\">";
        // line 21
        echo (isset($context["entry_code"]) ? $context["entry_code"] : null);
        echo "</label>
          <div class=\"col-sm-10\">
            <textarea cols=\"40\" rows=\"5\" placeholder=\"";
        // line 23
        echo (isset($context["entry_code"]) ? $context["entry_code"] : null);
        echo "\" id=\"input-code\" class=\"form-control\">";
        echo (isset($context["code"]) ? $context["code"] : null);
        echo "</textarea>
          </div>
        </div>
        <div class=\"form-group\">
          <label class=\"col-sm-2 control-label\" for=\"input-generator\"><span data-toggle=\"tooltip\" title=\"";
        // line 27
        echo (isset($context["help_generator"]) ? $context["help_generator"] : null);
        echo "\">";
        echo (isset($context["entry_generator"]) ? $context["entry_generator"] : null);
        echo "</span></label>
          <div class=\"col-sm-10\">
            <input type=\"text\" name=\"product\" value=\"\" placeholder=\"";
        // line 29
        echo (isset($context["entry_generator"]) ? $context["entry_generator"] : null);
        echo "\" id=\"input-generator\" class=\"form-control\" />
          </div>
        </div>
        <div class=\"form-group\">
          <label class=\"col-sm-2 control-label\" for=\"input-link\">";
        // line 33
        echo (isset($context["entry_link"]) ? $context["entry_link"] : null);
        echo "</label>
          <div class=\"col-sm-10\">
            <textarea name=\"link\" cols=\"40\" rows=\"5\" placeholder=\"";
        // line 35
        echo (isset($context["entry_link"]) ? $context["entry_link"] : null);
        echo "\" id=\"input-link\" class=\"form-control\"></textarea>
          </div>
        </div>
      </form>
      <div class=\"buttons clearfix\">
        <div class=\"pull-right\"><a href=\"";
        // line 40
        echo (isset($context["continue"]) ? $context["continue"] : null);
        echo "\" class=\"btn btn-primary\">";
        echo (isset($context["button_continue"]) ? $context["button_continue"] : null);
        echo "</a></div>
      </div>
      ";
        // line 42
        echo (isset($context["content_bottom"]) ? $context["content_bottom"] : null);
        echo "</div>
    ";
        // line 43
        echo (isset($context["column_right"]) ? $context["column_right"] : null);
        echo "</div>
</div>
<script type=\"text/javascript\"><!--
\$('input[name=\\'product\\']').autocomplete({
\t'source': function(request, response) {
\t\t\$.ajax({
\t\t\turl: 'index.php?route=account/tracking/autocomplete&filter_name=' +  encodeURIComponent(request) + '&tracking=' + encodeURIComponent(\$('#input-code').val()),
\t\t\tdataType: 'json',
\t\t\tsuccess: function(json) {
\t\t\t\tresponse(\$.map(json, function(item) {
\t\t\t\t\treturn {
\t\t\t\t\t\tlabel: item['name'],
\t\t\t\t\t\tvalue: item['link']
\t\t\t\t\t}
\t\t\t\t}));
\t\t\t}
\t\t});
\t},
\t'select': function(item) {
\t\t\$('input[name=\\'product\\']').val(item['label']);
\t\t\$('textarea[name=\\'link\\']').val(item['value']);
\t}
});
//--></script>
";
        // line 67
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "default/template/account/tracking.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  163 => 67,  136 => 43,  132 => 42,  125 => 40,  117 => 35,  112 => 33,  105 => 29,  98 => 27,  89 => 23,  84 => 21,  78 => 18,  74 => 17,  67 => 16,  64 => 15,  61 => 14,  58 => 13,  55 => 12,  52 => 11,  49 => 10,  47 => 9,  43 => 8,  40 => 7,  29 => 5,  25 => 4,  19 => 1,);
    }
}
/* {{ header }}*/
/* <div id="account-tracking" class="container">*/
/*   <ul class="breadcrumb">*/
/*     {% for breadcrumb in breadcrumbs %}*/
/*     <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*     {% endfor %}*/
/*   </ul>*/
/*   <div class="row">{{ column_left }}*/
/*     {% if column_left and column_right %}*/
/*     {% set class = 'col-sm-6' %}*/
/*     {% elseif column_left or column_right %}*/
/*     {% set class = 'col-sm-9' %}*/
/*     {% else %}*/
/*     {% set class = 'col-sm-12' %}*/
/*     {% endif %}*/
/*     <div id="content" class="{{ class }}">{{ content_top }}*/
/*       <h1>{{ heading_title }}</h1>*/
/*       <p>{{ text_description }}</p>*/
/*       <form class="form-horizontal">*/
/*         <div class="form-group">*/
/*           <label class="col-sm-2 control-label" for="input-code">{{ entry_code }}</label>*/
/*           <div class="col-sm-10">*/
/*             <textarea cols="40" rows="5" placeholder="{{ entry_code }}" id="input-code" class="form-control">{{ code }}</textarea>*/
/*           </div>*/
/*         </div>*/
/*         <div class="form-group">*/
/*           <label class="col-sm-2 control-label" for="input-generator"><span data-toggle="tooltip" title="{{ help_generator }}">{{ entry_generator }}</span></label>*/
/*           <div class="col-sm-10">*/
/*             <input type="text" name="product" value="" placeholder="{{ entry_generator }}" id="input-generator" class="form-control" />*/
/*           </div>*/
/*         </div>*/
/*         <div class="form-group">*/
/*           <label class="col-sm-2 control-label" for="input-link">{{ entry_link }}</label>*/
/*           <div class="col-sm-10">*/
/*             <textarea name="link" cols="40" rows="5" placeholder="{{ entry_link }}" id="input-link" class="form-control"></textarea>*/
/*           </div>*/
/*         </div>*/
/*       </form>*/
/*       <div class="buttons clearfix">*/
/*         <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>*/
/*       </div>*/
/*       {{ content_bottom }}</div>*/
/*     {{ column_right }}</div>*/
/* </div>*/
/* <script type="text/javascript"><!--*/
/* $('input[name=\'product\']').autocomplete({*/
/* 	'source': function(request, response) {*/
/* 		$.ajax({*/
/* 			url: 'index.php?route=account/tracking/autocomplete&filter_name=' +  encodeURIComponent(request) + '&tracking=' + encodeURIComponent($('#input-code').val()),*/
/* 			dataType: 'json',*/
/* 			success: function(json) {*/
/* 				response($.map(json, function(item) {*/
/* 					return {*/
/* 						label: item['name'],*/
/* 						value: item['link']*/
/* 					}*/
/* 				}));*/
/* 			}*/
/* 		});*/
/* 	},*/
/* 	'select': function(item) {*/
/* 		$('input[name=\'product\']').val(item['label']);*/
/* 		$('textarea[name=\'link\']').val(item['value']);*/
/* 	}*/
/* });*/
/* //--></script>*/
/* {{ footer }}*/
/* */
