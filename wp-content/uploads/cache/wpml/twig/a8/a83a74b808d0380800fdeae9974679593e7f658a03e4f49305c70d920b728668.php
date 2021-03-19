<?php

/* custom-prices.twig */
class __TwigTemplate_f9ee02e4040e2eaf1d9def29ba03dfe73d271f92ba88316c86f2ece7d4342be5 extends Twig_Template
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
        if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
            // line 2
            echo "    <tr><td>
";
        }
        // line 4
        echo "
<div class=\"wcml_custom_prices_block\">
    ";
        // line 6
        if (twig_test_empty((isset($context["currencies"]) ? $context["currencies"] : null))) {
            // line 7
            echo "        <div class=\"custom_prices_message_block\">
            <label>";
            // line 8
            echo $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "not_set", array());
            echo "</label>
        </div>
    ";
        } else {
            // line 11
            echo "        <div class=\"wcml_custom_prices_options_block\">

            <label for=\"wcml_custom_prices_auto[";
            // line 13
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\">
                <input type=\"radio\" name=\"_wcml_custom_prices[";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" id=\"wcml_custom_prices_auto[";
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" value=\"0\" class=\"wcml_custom_prices_input\" ";
            echo (isset($context["checked_calc_auto"]) ? $context["checked_calc_auto"] : null);
            echo " />
                ";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "calc_auto", array()), "html", null, true);
            echo "&nbsp;
                <span class=\"block_actions\" ";
            // line 16
            if ( !twig_test_empty((isset($context["checked_calc_auto"]) ? $context["checked_calc_auto"] : null))) {
                echo " style=\"display: inline;\" ";
            }
            echo ">(
                    <a href=\"\" class=\"wcml_custom_prices_auto_block_show\" title=\"";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "see_prices", array()));
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "show", array()), "html", null, true);
            echo "</a>
                    <a href=\"\" class=\"wcml_custom_prices_auto_block_hide\">";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "hide", array()), "html", null, true);
            echo "</a>
                )</span>
            </label>


            <label for=\"wcml_custom_prices_manually[";
            // line 23
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\">
                <input type=\"radio\" name=\"_wcml_custom_prices[";
            // line 24
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" value=\"1\" id=\"wcml_custom_prices_manually[";
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" class=\"wcml_custom_prices_input\" ";
            echo (isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null);
            echo " />
                ";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "set_manually", array()), "html", null, true);
            echo "
            </label>
            <div class=\"wcml_custom_prices_manually_block_control\">
                <a ";
            // line 28
            if ( !twig_test_empty((isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null))) {
                echo " style=\"display:none\" ";
            }
            echo " href=\"\" class=\"wcml_custom_prices_manually_block_show\">&raquo; ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "enter_prices", array()), "html", null, true);
            echo "</a>
                <a style=\"display:none\" href=\"\" class=\"wcml_custom_prices_manually_block_hide\">- ";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "hide_prices", array()), "html", null, true);
            echo "</a>
            </div>
        </div>

        <div class=\"wcml_custom_prices_manually_block\" ";
            // line 33
            if ( !twig_test_empty((isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null))) {
                echo " style=\"display: block;\" ";
            }
            echo ">
            ";
            // line 34
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["currencies"]) ? $context["currencies"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["currency"]) {
                // line 35
                echo "                <div class=\"currency_blck\">
                    <label>
                        ";
                // line 37
                echo $this->getAttribute($context["currency"], "currency_format", array());
                echo "
                    </label>

                    ";
                // line 40
                if (twig_test_empty($this->getAttribute($this->getAttribute($context["currency"], "custom_price", array()), "_regular_price", array(), "array"))) {
                    // line 41
                    echo "                        <span class=\"wcml_no_price_message\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "det_auto", array()), "html", null, true);
                    echo "</span>
                    ";
                }
                // line 43
                echo "
                    ";
                // line 44
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    // line 45
                    echo "                        ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "custom_price", array()));
                    foreach ($context['_seq'] as $context["key"] => $context["custom_price"]) {
                        // line 46
                        echo "                            <p>
                                <label>";
                        // line 47
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), $context["key"], array(), "array"), "html", null, true);
                        echo " ( ";
                        echo $this->getAttribute($context["currency"], "currency_symbol", array());
                        echo " )</label>
                                <input type=\"text\"
                                       name=\"_custom_variation";
                        // line 49
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                        echo "\"
                                       class=\"wc_input_price wcml_input_price short wcml";
                        // line 50
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\"
                                       value=\"";
                        // line 51
                        echo twig_escape_filter($this->env, $context["custom_price"], "html", null, true);
                        echo "\" step=\"any\" min=\"0\" />
                            </p>
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['custom_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 54
                    echo "                    ";
                } else {
                    // line 55
                    echo "                        ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "custom_html", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["custom_price_html"]) {
                        // line 56
                        echo "                            ";
                        echo $context["custom_price_html"];
                        echo "
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_price_html'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 58
                    echo "                    ";
                }
                // line 59
                echo "
                    <div class=\"wcml_schedule\">
                        <label>";
                // line 61
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "schedule", array()), "html", null, true);
                echo "</label>
                        <div class=\"wcml_schedule_options\">


                            <label for=\"wcml_schedule_auto[";
                // line 65
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\">
                                <input type=\"radio\" name=\"_wcml_schedule[";
                // line 66
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                       id=\"wcml_schedule_auto[";
                // line 67
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                       value=\"0\"
                                       class=\"wcml_schedule_input\" ";
                // line 69
                echo $this->getAttribute($context["currency"], "schedule_auto_checked", array());
                echo " />
                                ";
                // line 70
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "same_as_def", array()), "html", null, true);
                echo "
                            </label>


                            <label for=\"wcml_schedule_manually[";
                // line 74
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\">
                                <input type=\"radio\" name=\"_wcml_schedule[";
                // line 75
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                       value=\"1\"
                                       id=\"wcml_schedule_manually[";
                // line 77
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                       class=\"wcml_schedule_input\" ";
                // line 78
                echo $this->getAttribute($context["currency"], "schedule_man_checked", array());
                echo " />
                                ";
                // line 79
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "set_dates", array()), "html", null, true);
                echo "
                                <span class=\"block_actions\">(
                                    <a href=\"\" class=\"wcml_schedule_manually_block_show\">";
                // line 81
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "schedule", array()), "html", null, true);
                echo "</a>
                                    <a href=\"\" class=\"wcml_schedule_manually_block_hide\">";
                // line 82
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "collapse", array()), "html", null, true);
                echo "</a>
                                )</span>
                            </label>

                            <div class=\"wcml_schedule_dates\">
                                <input type=\"text\" class=\"short custom_sale_price_dates_from\"
                                       name=\"_custom";
                // line 88
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    echo "_variation";
                }
                echo "_sale_price_dates_from";
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       id=\"_custom_sale_price_dates_from";
                // line 89
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       value=\"";
                // line 90
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "sale_price_dates_from", array()));
                echo "\"
                                       placeholder=\"";
                // line 91
                echo $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "from", array());
                echo " YYYY-MM-DD\"
                                       maxlength=\"10\" pattern=\"[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])\" />

                                <input type=\"text\" class=\"short custom_sale_price_dates_to\"
                                       name=\"_custom";
                // line 95
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    echo "_variation";
                }
                echo "_sale_price_dates_to";
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       id=\"_custom_sale_price_dates_to";
                // line 96
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       value=\"";
                // line 97
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "sale_price_dates_to", array()));
                echo "\"
                                       placeholder=\"";
                // line 98
                echo $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "to", array());
                echo "  YYYY-MM-DD\"
                                       maxlength=\"10\" pattern=\"[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])\" />

                            </div>
                        </div>
                    </div>
                </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['currency'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 106
            echo "        </div>

        <div class=\"wcml_automaticaly_prices_block\">

            ";
            // line 110
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["currencies"]) ? $context["currencies"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["currency"]) {
                // line 111
                echo "                <label>";
                echo $this->getAttribute($context["currency"], "currency_format", array());
                echo "</label>

                ";
                // line 113
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    // line 114
                    echo "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "readonly_price", array()));
                    foreach ($context['_seq'] as $context["key"] => $context["readonly_price"]) {
                        // line 115
                        echo "                        <p>
                            <label>";
                        // line 116
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), $context["key"], array(), "array"), "html", null, true);
                        echo " ( ";
                        echo $this->getAttribute($context["currency"], "currency_symbol", array());
                        echo " )</label>
                            <input type=\"text\"
                                   name=\"_readonly";
                        // line 118
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\"
                                   class=\"wc_input_price short\"
                                   value=\"";
                        // line 120
                        echo twig_escape_filter($this->env, $context["readonly_price"]);
                        echo "\"
                                   step=\"any\" min=\"0\" readonly = \"readonly\"
                                   rel=\"";
                        // line 122
                        echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "rate", array()));
                        echo "\" />
                        </p>
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['readonly_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 125
                    echo "                ";
                } else {
                    // line 126
                    echo "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "readonly_html", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["readonly_html_price"]) {
                        // line 127
                        echo "                        ";
                        echo $context["readonly_html_price"];
                        echo "
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['readonly_html_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 129
                    echo "                ";
                }
                // line 130
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['currency'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 131
            echo "        </div>
    ";
        }
        // line 133
        echo "
    ";
        // line 134
        if (twig_test_empty((isset($context["is_variation"]) ? $context["is_variation"] : null))) {
            // line 135
            echo "        <div class=\"wcml_price_error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "enter_price", array()), "html", null, true);
            echo "</div>
    ";
        }
        // line 137
        echo "</div>

";
        // line 139
        if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
            // line 140
            echo "    </td></tr>
";
        }
    }

    public function getTemplateName()
    {
        return "custom-prices.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  433 => 140,  431 => 139,  427 => 137,  421 => 135,  419 => 134,  416 => 133,  412 => 131,  406 => 130,  403 => 129,  394 => 127,  389 => 126,  386 => 125,  377 => 122,  372 => 120,  367 => 118,  360 => 116,  357 => 115,  352 => 114,  350 => 113,  344 => 111,  340 => 110,  334 => 106,  320 => 98,  316 => 97,  312 => 96,  304 => 95,  297 => 91,  293 => 90,  289 => 89,  281 => 88,  272 => 82,  268 => 81,  263 => 79,  259 => 78,  253 => 77,  246 => 75,  240 => 74,  233 => 70,  229 => 69,  222 => 67,  216 => 66,  210 => 65,  203 => 61,  199 => 59,  196 => 58,  187 => 56,  182 => 55,  179 => 54,  170 => 51,  166 => 50,  161 => 49,  154 => 47,  151 => 46,  146 => 45,  144 => 44,  141 => 43,  135 => 41,  133 => 40,  127 => 37,  123 => 35,  119 => 34,  113 => 33,  106 => 29,  98 => 28,  92 => 25,  84 => 24,  80 => 23,  72 => 18,  66 => 17,  60 => 16,  56 => 15,  48 => 14,  44 => 13,  40 => 11,  34 => 8,  31 => 7,  29 => 6,  25 => 4,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "custom-prices.twig", "/htdocs/wp-content/plugins/woocommerce-multilingual/templates/multi-currency/custom-prices.twig");
    }
}
