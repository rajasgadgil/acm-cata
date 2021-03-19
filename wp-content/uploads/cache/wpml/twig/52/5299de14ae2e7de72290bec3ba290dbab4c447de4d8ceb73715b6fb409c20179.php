<?php

/* filter.twig */
class __TwigTemplate_b529408c42fbf8033ef42e304c8907b21b05d6322762d9cafc0646ae2aa3f4c9 extends Twig_Template
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
            echo "\t<div class=\"tablenav top clearfix wcml-product-translation-filtering\">
\t\t<div class=\"alignleft\">
\t\t\t<select class=\"wcml_translation_status_lang\">
\t\t\t\t<option value=\"all\" ";
            // line 5
            if ( !(isset($context["slang"]) ? $context["slang"] : null)) {
                echo " selected=\"selected\" ";
            }
            echo ">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "all_lang", array()), "html", null, true);
            echo "</option>
\t\t\t\t";
            // line 6
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["active_languages"]) ? $context["active_languages"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["language"]) {
                // line 7
                echo "\t\t\t\t\t<option\tvalue=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "code", array()));
                echo "\" ";
                if (((isset($context["slang"]) ? $context["slang"] : null) == $this->getAttribute($context["language"], "code", array()))) {
                    echo " selected=\"selected\" ";
                }
                echo " >
\t\t\t\t\t\t";
                // line 8
                echo twig_escape_filter($this->env, $this->getAttribute($context["language"], "display_name", array()), "html", null, true);
                echo "
\t\t\t\t\t</option>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['language'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            echo "\t\t\t</select>

\t\t\t<select class=\"wcml_product_category\">
\t\t\t\t<option value=\"0\">";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "all_cats", array()), "html", null, true);
            echo "</option>
\t\t\t\t";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                // line 16
                echo "\t\t\t\t\t<option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["category"], "term_taxonomy_id", array()));
                echo "\" ";
                if (((isset($context["category_from_filter"]) ? $context["category_from_filter"] : null) == $this->getAttribute($context["category"], "term_taxonomy_id", array()))) {
                    echo " selected=\"selected\" ";
                }
                echo ">
\t\t\t\t\t\t";
                // line 17
                echo twig_escape_filter($this->env, $this->getAttribute($context["category"], "name", array()), "html", null, true);
                echo "
\t\t\t\t\t</option>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 20
            echo "\t\t\t</select>

\t\t\t<select class=\"wcml_translation_status\">
\t\t\t\t<option value=\"all\">";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "all_trnsl_stats", array()), "html", null, true);
            echo "</option>
\t\t\t\t<option value=\"not\" ";
            // line 24
            if (((isset($context["trst"]) ? $context["trst"] : null) == "not")) {
                echo " selected=\"selected\" ";
            }
            echo ">
\t\t\t\t\t";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "not_trnsl", array()), "html", null, true);
            echo "
\t\t\t\t</option>
\t\t\t\t<option value=\"need_update\" ";
            // line 27
            if (((isset($context["trst"]) ? $context["trst"] : null) == "need_update")) {
                echo " selected=\"selected\" ";
            }
            echo ">
\t\t\t\t\t";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "need_upd", array()), "html", null, true);
            echo "
\t\t\t\t</option>
\t\t\t\t<option value=\"in_progress\" ";
            // line 30
            if (((isset($context["trst"]) ? $context["trst"] : null) == "in_progress")) {
                echo " selected=\"selected\" ";
            }
            echo ">
\t\t\t\t\t";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "in_progress", array()), "html", null, true);
            echo "
\t\t\t\t</option>
\t\t\t\t<option value=\"complete\" ";
            // line 33
            if (((isset($context["trst"]) ? $context["trst"] : null) == "complete")) {
                echo " selected=\"selected\" ";
            }
            echo ">
\t\t\t\t\t";
            // line 34
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "complete", array()), "html", null, true);
            echo "
\t\t\t\t</option>
\t\t\t</select>

\t\t\t<select class=\"wcml_product_status\">
\t\t\t\t<option value=\"all\">";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "all_stats", array()), "html", null, true);
            echo "</option>
\t\t\t\t";
            // line 40
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["all_statuses"]) ? $context["all_statuses"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["status"]) {
                // line 41
                echo "\t\t\t\t\t<option value=\"";
                echo twig_escape_filter($this->env, $context["status"]);
                echo "\" ";
                if (((isset($context["st"]) ? $context["st"] : null) == $context["status"])) {
                    echo " selected=\"selected\" ";
                }
                echo ">
\t\t\t\t\t\t";
                // line 42
                echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $context["status"]), "html", null, true);
                echo "
\t\t\t\t\t</option>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['status'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            echo "\t\t\t</select>

\t\t\t<button type=\"button\" value=\"filter\" class=\"button-secondary wcml_search\">";
            // line 47
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "filter", array()), "html", null, true);
            echo "</button>
\t\t\t";
            // line 48
            if ((isset($context["search"]) ? $context["search"] : null)) {
                // line 49
                echo "\t\t\t\t<button type=\"button\" value=\"reset\" class=\"button-secondary wcml_reset_search\">
\t\t\t\t\t";
                // line 50
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "reset", array()), "html", null, true);
                echo "
\t\t\t\t</button>
\t\t\t";
            }
            // line 53
            echo "\t\t</div>

\t\t<div class=\"alignright\">
\t\t\t<input type=\"search\" class=\"wcml_product_name\" placeholder=\"";
            // line 56
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "search", array()));
            echo "\" value=\"";
            echo twig_escape_filter($this->env, (isset($context["search_text"]) ? $context["search_text"] : null), "html", null, true);
            echo "\"/>
\t\t\t<input type=\"hidden\" value=\"";
            // line 57
            echo twig_escape_filter($this->env, (isset($context["products_admin_url"]) ? $context["products_admin_url"] : null), "html", null, true);
            echo "\" class=\"wcml_products_admin_url\"/>
\t\t\t<input type=\"hidden\" value=\"";
            // line 58
            echo twig_escape_filter($this->env, (isset($context["pagination_url"]) ? $context["pagination_url"] : null), "html", null, true);
            echo "\" class=\"wcml_pagination_url\"/>
\t\t\t<button type=\"button\" value=\"search\" class=\"button-secondary wcml_search_by_title\">";
            // line 59
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "search", array()), "html", null, true);
            echo "</button>
\t\t</div>
\t</div>
";
        }
    }

    public function getTemplateName()
    {
        return "filter.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  207 => 59,  203 => 58,  199 => 57,  193 => 56,  188 => 53,  182 => 50,  179 => 49,  177 => 48,  173 => 47,  169 => 45,  160 => 42,  151 => 41,  147 => 40,  143 => 39,  135 => 34,  129 => 33,  124 => 31,  118 => 30,  113 => 28,  107 => 27,  102 => 25,  96 => 24,  92 => 23,  87 => 20,  78 => 17,  69 => 16,  65 => 15,  61 => 14,  56 => 11,  47 => 8,  38 => 7,  34 => 6,  26 => 5,  21 => 2,  19 => 1,);
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
\t<div class=\"tablenav top clearfix wcml-product-translation-filtering\">
\t\t<div class=\"alignleft\">
\t\t\t<select class=\"wcml_translation_status_lang\">
\t\t\t\t<option value=\"all\" {% if not slang %} selected=\"selected\" {% endif %}>{{ strings.all_lang }}</option>
\t\t\t\t{% for language in active_languages %}
\t\t\t\t\t<option\tvalue=\"{{ language.code|e }}\" {% if slang == language.code  %} selected=\"selected\" {% endif %} >
\t\t\t\t\t\t{{ language.display_name }}
\t\t\t\t\t</option>
\t\t\t\t{% endfor %}
\t\t\t</select>

\t\t\t<select class=\"wcml_product_category\">
\t\t\t\t<option value=\"0\">{{ strings.all_cats }}</option>
\t\t\t\t{% for category in categories %}
\t\t\t\t\t<option value=\"{{ category.term_taxonomy_id|e }}\" {% if category_from_filter == category.term_taxonomy_id %} selected=\"selected\" {% endif %}>
\t\t\t\t\t\t{{ category.name }}
\t\t\t\t\t</option>
\t\t\t\t{% endfor %}
\t\t\t</select>

\t\t\t<select class=\"wcml_translation_status\">
\t\t\t\t<option value=\"all\">{{ strings.all_trnsl_stats }}</option>
\t\t\t\t<option value=\"not\" {% if trst == 'not' %} selected=\"selected\" {% endif %}>
\t\t\t\t\t{{ strings.not_trnsl }}
\t\t\t\t</option>
\t\t\t\t<option value=\"need_update\" {% if trst == 'need_update' %} selected=\"selected\" {% endif %}>
\t\t\t\t\t{{ strings.need_upd }}
\t\t\t\t</option>
\t\t\t\t<option value=\"in_progress\" {% if trst == 'in_progress' %} selected=\"selected\" {% endif %}>
\t\t\t\t\t{{ strings.in_progress }}
\t\t\t\t</option>
\t\t\t\t<option value=\"complete\" {% if trst == 'complete' %} selected=\"selected\" {% endif %}>
\t\t\t\t\t{{ strings.complete }}
\t\t\t\t</option>
\t\t\t</select>

\t\t\t<select class=\"wcml_product_status\">
\t\t\t\t<option value=\"all\">{{ strings.all_stats }}</option>
\t\t\t\t{% for status in all_statuses %}
\t\t\t\t\t<option value=\"{{ status|e }}\" {% if st == status %} selected=\"selected\" {% endif %}>
\t\t\t\t\t\t{{ status|capitalize }}
\t\t\t\t\t</option>
\t\t\t\t{% endfor %}
\t\t\t</select>

\t\t\t<button type=\"button\" value=\"filter\" class=\"button-secondary wcml_search\">{{ strings.filter }}</button>
\t\t\t{% if search %}
\t\t\t\t<button type=\"button\" value=\"reset\" class=\"button-secondary wcml_reset_search\">
\t\t\t\t\t{{ strings.reset }}
\t\t\t\t</button>
\t\t\t{% endif %}
\t\t</div>

\t\t<div class=\"alignright\">
\t\t\t<input type=\"search\" class=\"wcml_product_name\" placeholder=\"{{ strings.search|e }}\" value=\"{{ search_text }}\"/>
\t\t\t<input type=\"hidden\" value=\"{{ products_admin_url }}\" class=\"wcml_products_admin_url\"/>
\t\t\t<input type=\"hidden\" value=\"{{ pagination_url }}\" class=\"wcml_pagination_url\"/>
\t\t\t<button type=\"button\" value=\"search\" class=\"button-secondary wcml_search_by_title\">{{ strings.search }}</button>
\t\t</div>
\t</div>
{% endif %}", "filter.twig", "/htdocs/wp-content/plugins/woocommerce-multilingual/templates/products-list/filter.twig");
    }
}
