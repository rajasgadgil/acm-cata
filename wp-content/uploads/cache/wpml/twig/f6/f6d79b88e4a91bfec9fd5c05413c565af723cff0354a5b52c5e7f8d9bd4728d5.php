<?php

/* pagination.twig */
class __TwigTemplate_098478e88370846328ac2391cb668aa55d88f87948e284f03b732a931d9642b2 extends Twig_Template
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
        if ((isset($context["display"]) ? $context["display"] : null)) {
            // line 2
            echo "\t<div class=\"tablenav bottom clearfix\">
\t\t<div class=\"tablenav-pages\">
\t\t\t<span class=\"displaying-num\">";
            // line 4
            echo twig_escape_filter($this->env, sprintf($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "items", array()), (isset($context["products_count"]) ? $context["products_count"] : null)), "html", null, true);
            echo "</span>
\t\t\t<span class=\"pagination-links\">
\t\t\t\t";
            // line 6
            if ((isset($context["show"]) ? $context["show"] : null)) {
                // line 7
                echo "\t\t\t\t\t<a class=\"first-page ";
                if (((isset($context["pn"]) ? $context["pn"] : null) == 1)) {
                    echo " disabled ";
                }
                echo "\"
\t\t\t\t\t   href=\"";
                // line 8
                echo twig_escape_filter($this->env, (isset($context["pagination_first"]) ? $context["pagination_first"] : null), "html", null, true);
                echo "\"
\t\t\t\t\t   title=\"";
                // line 9
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "first", array()));
                echo "\">&laquo;</a>

\t\t\t\t\t<a class=\"prev-page ";
                // line 11
                if (((isset($context["pn"]) ? $context["pn"] : null) == 1)) {
                    echo " disabled ";
                }
                echo "\"
\t\t\t\t\t   href=\"";
                // line 12
                echo twig_escape_filter($this->env, (isset($context["pagination_prev"]) ? $context["pagination_prev"] : null), "html", null, true);
                echo "\"
\t\t\t\t\t   title=\"";
                // line 13
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "previous", array()));
                echo "\">&lsaquo;</a>

\t\t\t\t\t<span class=\"paging-input\">
\t\t\t\t\t\t<label for=\"current-page-selector\" class=\"screen-reader-text\">
\t\t\t\t\t\t\t";
                // line 17
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "select", array()), "html", null, true);
                echo "
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input class=\"current-page\" id=\"current-page-selector\"
\t\t\t\t\t\t\t   title=\"";
                // line 20
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "current", array()));
                echo "\"
\t\t\t\t\t\t\t   type=\"text\" name=\"paged\" value=\"";
                // line 21
                echo twig_escape_filter($this->env, (isset($context["pn"]) ? $context["pn"] : null), "html", null, true);
                echo "\" size=\"2\">
\t\t\t\t\t\t&nbsp;";
                // line 22
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "of", array()), "html", null, true);
                echo "&nbsp;
\t\t\t\t\t\t<span\tclass=\"total-pages\">";
                // line 23
                echo twig_escape_filter($this->env, (isset($context["last"]) ? $context["last"] : null), "html", null, true);
                echo "</span>
\t\t\t\t\t</span>

\t\t\t\t\t<a class=\"next-page ";
                // line 26
                if (((isset($context["pn"]) ? $context["pn"] : null) == (isset($context["last"]) ? $context["last"] : null))) {
                    echo " disabled ";
                }
                echo "\"
\t\t\t\t\t   href=\"";
                // line 27
                echo twig_escape_filter($this->env, (isset($context["pagination_next"]) ? $context["pagination_next"] : null), "html", null, true);
                echo "\"
\t\t\t\t\t   title=\"";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "next", array()));
                echo "\">&rsaquo;</a>

\t\t\t\t\t<a class=\"last-page ";
                // line 30
                if (((isset($context["pn"]) ? $context["pn"] : null) == (isset($context["last"]) ? $context["last"] : null))) {
                    echo " disabled ";
                }
                echo "\"
\t\t\t\t\t   href=\"";
                // line 31
                echo twig_escape_filter($this->env, (isset($context["pagination_last"]) ? $context["pagination_last"] : null), "html", null, true);
                echo "\"
\t\t\t\t\t   title=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "last", array()));
                echo "\">&raquo;</a>
\t\t\t\t";
            }
            // line 34
            echo "\t\t\t</span>
\t\t</div>
\t</div>
";
        }
    }

    public function getTemplateName()
    {
        return "pagination.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 34,  114 => 32,  110 => 31,  104 => 30,  99 => 28,  95 => 27,  89 => 26,  83 => 23,  79 => 22,  75 => 21,  71 => 20,  65 => 17,  58 => 13,  54 => 12,  48 => 11,  43 => 9,  39 => 8,  32 => 7,  30 => 6,  25 => 4,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% if display %}
\t<div class=\"tablenav bottom clearfix\">
\t\t<div class=\"tablenav-pages\">
\t\t\t<span class=\"displaying-num\">{{ strings.items|format( products_count ) }}</span>
\t\t\t<span class=\"pagination-links\">
\t\t\t\t{% if show %}
\t\t\t\t\t<a class=\"first-page {% if pn == 1 %} disabled {% endif %}\"
\t\t\t\t\t   href=\"{{ pagination_first }}\"
\t\t\t\t\t   title=\"{{ strings.first|e }}\">&laquo;</a>

\t\t\t\t\t<a class=\"prev-page {% if pn == 1 %} disabled {% endif %}\"
\t\t\t\t\t   href=\"{{ pagination_prev }}\"
\t\t\t\t\t   title=\"{{ strings.previous|e }}\">&lsaquo;</a>

\t\t\t\t\t<span class=\"paging-input\">
\t\t\t\t\t\t<label for=\"current-page-selector\" class=\"screen-reader-text\">
\t\t\t\t\t\t\t{{ strings.select }}
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input class=\"current-page\" id=\"current-page-selector\"
\t\t\t\t\t\t\t   title=\"{{ strings.current|e }}\"
\t\t\t\t\t\t\t   type=\"text\" name=\"paged\" value=\"{{ pn }}\" size=\"2\">
\t\t\t\t\t\t&nbsp;{{ strings.of }}&nbsp;
\t\t\t\t\t\t<span\tclass=\"total-pages\">{{ last }}</span>
\t\t\t\t\t</span>

\t\t\t\t\t<a class=\"next-page {% if pn == last %} disabled {% endif %}\"
\t\t\t\t\t   href=\"{{ pagination_next }}\"
\t\t\t\t\t   title=\"{{ strings.next|e }}\">&rsaquo;</a>

\t\t\t\t\t<a class=\"last-page {% if pn == last %} disabled {% endif %}\"
\t\t\t\t\t   href=\"{{ pagination_last }}\"
\t\t\t\t\t   title=\"{{ strings.last|e }}\">&raquo;</a>
\t\t\t\t{% endif %}
\t\t\t</span>
\t\t</div>
\t</div>
{% endif %}", "pagination.twig", "/htdocs/wp-content/plugins/woocommerce-multilingual/templates/products-list/pagination.twig");
    }
}
