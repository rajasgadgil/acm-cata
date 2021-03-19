<?php

/* pointer-ui.twig */
class __TwigTemplate_2f0ffcc3447553d67c2a4b439e8291681143618f6e7dd159b81ad87995949bf7 extends Twig_Template
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
        echo "<div class=\"wcml-pointer-block\" data-selector=\"";
        echo twig_escape_filter($this->env, (isset($context["selector"]) ? $context["selector"] : null), "html", null, true);
        echo "\" data-insert-method=\"";
        echo twig_escape_filter($this->env, (isset($context["insert_method"]) ? $context["insert_method"] : null), "html", null, true);
        echo "\" style=\"display:none;\">
    <a id=\"wcml-pointer-target-";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["pointer"]) ? $context["pointer"] : null), "html", null, true);
        echo "\" href=\"javascript:void(0)\" class=\"otgs-ico-wpml wcml-pointer-link\"
        data-wcml-open-pointer=\"wcml-pointer-";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["pointer"]) ? $context["pointer"] : null), "html", null, true);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["description"]) ? $context["description"] : null), "trnsl_title", array()));
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["description"]) ? $context["description"] : null), "trnsl_title", array()));
        echo "</a>

    <div id=\"wcml-pointer-";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["pointer"]) ? $context["pointer"] : null), "html", null, true);
        echo "\" style=\"display:none;\">
        <div class=\"wcml-pointer-inner\">
            <div class=\"wcml-message-content wcml-table-cell\">
                <p class=\"wcml-information-paragraph\">
                    ";
        // line 9
        echo $this->getAttribute((isset($context["description"]) ? $context["description"] : null), "content", array());
        echo "
                </p>
                <p class=\"wcml-information-link\">
                    <a class=\"wcml-external-link\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["description"]) ? $context["description"] : null), "doc_link", array()), "html", null, true);
        echo "\" target=\"_blank\">
                        ";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["description"]) ? $context["description"] : null), "doc_link_text", array()), "html", null, true);
        echo "
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "pointer-ui.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 13,  52 => 12,  46 => 9,  39 => 5,  30 => 3,  26 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"wcml-pointer-block\" data-selector=\"{{ selector }}\" data-insert-method=\"{{ insert_method }}\" style=\"display:none;\">
    <a id=\"wcml-pointer-target-{{ pointer }}\" href=\"javascript:void(0)\" class=\"otgs-ico-wpml wcml-pointer-link\"
        data-wcml-open-pointer=\"wcml-pointer-{{ pointer }}\" title=\"{{ description.trnsl_title|e }}\">{{ description.trnsl_title|e }}</a>

    <div id=\"wcml-pointer-{{ pointer }}\" style=\"display:none;\">
        <div class=\"wcml-pointer-inner\">
            <div class=\"wcml-message-content wcml-table-cell\">
                <p class=\"wcml-information-paragraph\">
                    {{ description.content|raw }}
                </p>
                <p class=\"wcml-information-link\">
                    <a class=\"wcml-external-link\" href=\"{{ description.doc_link }}\" target=\"_blank\">
                        {{ description.doc_link_text }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>", "pointer-ui.twig", "/htdocs/wp-content/plugins/woocommerce-multilingual/templates/pointer-ui.twig");
    }
}
