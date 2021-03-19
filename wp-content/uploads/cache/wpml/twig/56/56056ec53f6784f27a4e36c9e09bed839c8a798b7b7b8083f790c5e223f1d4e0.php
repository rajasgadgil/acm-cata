<?php

/* template.twig */
class __TwigTemplate_707963a41128964fc5a9b49383889cfdf76849de47fc51f7c1b7646129d7990c extends Twig_Template
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
        $context["css_classes_flag"] = twig_trim_filter(("wpml-ls-flag " . $this->getAttribute((isset($context["backward_compatibility"]) ? $context["backward_compatibility"] : null), "css_classes_flag", array())));
        // line 2
        $context["css_classes_native"] = twig_trim_filter(("wpml-ls-native " . $this->getAttribute((isset($context["backward_compatibility"]) ? $context["backward_compatibility"] : null), "css_classes_native", array())));
        // line 3
        $context["css_classes_display"] = twig_trim_filter(("wpml-ls-display " . $this->getAttribute((isset($context["backward_compatibility"]) ? $context["backward_compatibility"] : null), "css_classes_display", array())));
        // line 4
        $context["css_classes_bracket"] = twig_trim_filter(("wpml-ls-bracket " . $this->getAttribute((isset($context["backward_compatibility"]) ? $context["backward_compatibility"] : null), "css_classes_bracket", array())));
        // line 6
        if ((isset($context["flag_url"]) ? $context["flag_url"] : null)) {
            // line 7
            echo "<img class=\"";
            echo twig_escape_filter($this->env, (isset($context["css_classes_flag"]) ? $context["css_classes_flag"] : null), "html", null, true);
            echo "\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["flag_url"]) ? $context["flag_url"] : null), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, (isset($context["code"]) ? $context["code"] : null), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, (isset($context["flag_title"]) ? $context["flag_title"] : null), "html", null, true);
            echo "\">";
        }
        // line 10
        if ((isset($context["native_name"]) ? $context["native_name"] : null)) {
            // line 11
            echo "<span class=\"";
            echo twig_escape_filter($this->env, (isset($context["css_classes_native"]) ? $context["css_classes_native"] : null), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["native_name"]) ? $context["native_name"] : null), "html", null, true);
            echo "</span>";
        }
        // line 14
        if ((isset($context["display_name"]) ? $context["display_name"] : null)) {
            // line 15
            echo "<span class=\"";
            echo twig_escape_filter($this->env, (isset($context["css_classes_display"]) ? $context["css_classes_display"] : null), "html", null, true);
            echo "\">";
            // line 16
            if ((isset($context["native_name"]) ? $context["native_name"] : null)) {
                echo "<span class=\"";
                echo twig_escape_filter($this->env, (isset($context["css_classes_bracket"]) ? $context["css_classes_bracket"] : null), "html", null, true);
                echo "\"> (</span>";
            }
            // line 17
            echo twig_escape_filter($this->env, (isset($context["display_name"]) ? $context["display_name"] : null), "html", null, true);
            // line 18
            if ((isset($context["native_name"]) ? $context["native_name"] : null)) {
                echo "<span class=\"";
                echo twig_escape_filter($this->env, (isset($context["css_classes_bracket"]) ? $context["css_classes_bracket"] : null), "html", null, true);
                echo "\">)</span>";
            }
            // line 19
            echo "</span>";
        }
    }

    public function getTemplateName()
    {
        return "template.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 19,  63 => 18,  61 => 17,  55 => 16,  51 => 15,  49 => 14,  42 => 11,  40 => 10,  29 => 7,  27 => 6,  25 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% set css_classes_flag    = ('wpml-ls-flag ' ~ backward_compatibility.css_classes_flag)|trim %}
{% set css_classes_native  = ('wpml-ls-native ' ~ backward_compatibility.css_classes_native)|trim %}
{% set css_classes_display = ('wpml-ls-display ' ~ backward_compatibility.css_classes_display)|trim %}
{% set css_classes_bracket = ('wpml-ls-bracket ' ~ backward_compatibility.css_classes_bracket)|trim %}

{%- if flag_url -%}
    <img class=\"{{ css_classes_flag }}\" src=\"{{ flag_url }}\" alt=\"{{ code }}\" title=\"{{ flag_title }}\">
{%- endif -%}

{%- if native_name -%}
    <span class=\"{{ css_classes_native }}\">{{ native_name }}</span>
{%- endif -%}

{%- if display_name -%}
    <span class=\"{{ css_classes_display }}\">
    {%- if native_name -%}<span class=\"{{ css_classes_bracket }}\"> (</span>{%- endif -%}
        {{- display_name -}}
        {%- if native_name -%}<span class=\"{{ css_classes_bracket }}\">)</span>{%- endif -%}
    </span>
{%- endif -%}", "template.twig", "/htdocs/wp-content/plugins/sitepress-multilingual-cms/templates/language-switchers/menu-item/template.twig");
    }
}
