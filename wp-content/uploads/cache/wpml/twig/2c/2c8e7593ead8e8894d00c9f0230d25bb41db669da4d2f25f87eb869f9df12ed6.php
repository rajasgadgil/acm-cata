<?php

/* trnsl-attributes.twig */
class __TwigTemplate_cbac0826a7829f91fb8d414e871a314f9cccc5deb0e5f4d349508bd7f853308c extends Twig_Template
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
        if (($context["edit_mode"] ?? null)) {
            // line 2
            echo "    <div class=\"wcml-is-translatable-attr-block\" style=\"display: none\">
        <table>
            <tr class=\"form-field\">
                <th scope=\"row\" valign=\"top\">
                    <label for=\"wcml-is-translatable-attr\">";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "label", array()), "html", null, true);
            echo "</label>
                </th>
                <td>
                    <input name=\"wcml-is-translatable-attr\" id=\"wcml-is-translatable-attr\" type=\"checkbox\" value=\"1\" ";
            // line 9
            if (($context["checked"] ?? null)) {
                echo " checked=\"checked\" ";
            }
            echo " />
                    <p class=\"description\">";
            // line 10
            echo twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "description", array()), "html", null, true);
            echo "</p>
                </td>
            </tr>
        </table>
    </div>
    <input type=\"hidden\" id=\"wcml-is-translatable-attr-notice\" value=\"";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "notice", array()), "html", null, true);
            echo "\" />
";
        } else {
            // line 17
            echo "    <div class=\"wcml-is-translatable-attr-block\" style=\"display: none\">
        <div class=\"form-field\">
            <label for=\"wcml-is-translatable-attr\">
                <input name=\"wcml-is-translatable-attr\" id=\"wcml-is-translatable-attr\" type=\"checkbox\" value=\"1\" ";
            // line 20
            if (($context["checked"] ?? null)) {
                echo " checked=\"checked\" ";
            }
            echo " />
                ";
            // line 21
            echo twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "label", array()), "html", null, true);
            echo "
            </label>
            <p class=\"description\">";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "description", array()), "html", null, true);
            echo "</p>
        </div>
    </div>
";
        }
    }

    public function getTemplateName()
    {
        return "trnsl-attributes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 23,  63 => 21,  57 => 20,  52 => 17,  47 => 15,  39 => 10,  33 => 9,  27 => 6,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "trnsl-attributes.twig", "/home/acmcata/acm-caraibes.com/wp-content/plugins/woocommerce-multilingual/templates/trnsl-attributes.twig");
    }
}
