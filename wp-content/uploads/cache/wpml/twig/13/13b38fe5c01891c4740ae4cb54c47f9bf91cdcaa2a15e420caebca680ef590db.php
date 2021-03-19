<?php

/* custom-prices.twig */
class __TwigTemplate_8df60f48d73b1763072cd105aa5763c8407c42a970317f3904302b250b6370aa extends Twig_Template
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

            <input type=\"radio\" name=\"_wcml_custom_prices[";
            // line 13
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" id=\"wcml_custom_prices_auto[";
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" value=\"0\" class=\"wcml_custom_prices_input\" ";
            echo (isset($context["checked_calc_auto"]) ? $context["checked_calc_auto"] : null);
            echo " />
            <label for=\"wcml_custom_prices_auto[";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "calc_auto", array()), "html", null, true);
            echo "&nbsp;
                <span class=\"block_actions\" ";
            // line 15
            if ( !twig_test_empty((isset($context["checked_calc_auto"]) ? $context["checked_calc_auto"] : null))) {
                echo " style=\"display: inline;\" ";
            }
            echo ">(
                    <a href=\"\" class=\"wcml_custom_prices_auto_block_show\" title=\"";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "see_prices", array()));
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "show", array()), "html", null, true);
            echo "</a>
                    <a href=\"\" class=\"wcml_custom_prices_auto_block_hide\">";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "hide", array()), "html", null, true);
            echo "</a>
                )</span>
            </label>


            <input type=\"radio\" name=\"_wcml_custom_prices[";
            // line 22
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" value=\"1\" id=\"wcml_custom_prices_manually[";
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\" class=\"wcml_custom_prices_input\" ";
            echo (isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null);
            echo " />
            <label for=\"wcml_custom_prices_manually[";
            // line 23
            echo twig_escape_filter($this->env, (isset($context["product_id"]) ? $context["product_id"] : null), "html", null, true);
            echo "]\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "set_manually", array()), "html", null, true);
            echo "</label>
            <div class=\"wcml_custom_prices_manually_block_control\">
                <a ";
            // line 25
            if ( !twig_test_empty((isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null))) {
                echo " style=\"display:none\" ";
            }
            echo " href=\"\" class=\"wcml_custom_prices_manually_block_show\">&raquo; ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "enter_prices", array()), "html", null, true);
            echo "</a>
                <a style=\"display:none\" href=\"\" class=\"wcml_custom_prices_manually_block_hide\">- ";
            // line 26
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "hide_prices", array()), "html", null, true);
            echo "</a>
            </div>
        </div>

        <div class=\"wcml_custom_prices_manually_block\" ";
            // line 30
            if ( !twig_test_empty((isset($context["checked_calc_manually"]) ? $context["checked_calc_manually"] : null))) {
                echo " style=\"display: block;\" ";
            }
            echo ">
            ";
            // line 31
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["currencies"]) ? $context["currencies"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["currency"]) {
                // line 32
                echo "                <div class=\"currency_blck\">
                    <label>
                        ";
                // line 34
                echo $this->getAttribute($context["currency"], "currency_format", array());
                echo "
                    </label>

                    ";
                // line 37
                if (twig_test_empty($this->getAttribute($this->getAttribute($context["currency"], "custom_price", array()), "_regular_price", array(), "array"))) {
                    // line 38
                    echo "                        <span class=\"wcml_no_price_message\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "det_auto", array()), "html", null, true);
                    echo "</span>
                    ";
                }
                // line 40
                echo "
                    ";
                // line 41
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    // line 42
                    echo "                        ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "custom_price", array()));
                    foreach ($context['_seq'] as $context["key"] => $context["custom_price"]) {
                        // line 43
                        echo "                            <p>
                                <label>";
                        // line 44
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), $context["key"], array(), "array"), "html", null, true);
                        echo " ( ";
                        echo $this->getAttribute($context["currency"], "currency_symbol", array());
                        echo " )</label>
                                <input type=\"text\"
                                       name=\"_custom_variation";
                        // line 46
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                        echo "\"
                                       class=\"wc_input_price wcml_input_price short wcml";
                        // line 47
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\"
                                       value=\"";
                        // line 48
                        echo twig_escape_filter($this->env, $context["custom_price"], "html", null, true);
                        echo "\" step=\"any\" min=\"0\" />
                            </p>
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['custom_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 51
                    echo "                    ";
                } else {
                    // line 52
                    echo "                        ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "custom_html", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["custom_price_html"]) {
                        // line 53
                        echo "                            ";
                        echo $context["custom_price_html"];
                        echo "
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_price_html'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 55
                    echo "                    ";
                }
                // line 56
                echo "
                    <div class=\"wcml_schedule\">
                        <label>";
                // line 58
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "schedule", array()), "html", null, true);
                echo "</label>
                        <div class=\"wcml_schedule_options\">

                            <input type=\"radio\" name=\"_wcml_schedule[";
                // line 61
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                   id=\"wcml_schedule_auto[";
                // line 62
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                   value=\"0\"
                                   class=\"wcml_schedule_input\" ";
                // line 64
                echo $this->getAttribute($context["currency"], "schedule_auto_checked", array());
                echo " />
                            <label for=\"wcml_schedule_auto[";
                // line 65
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "same_as_def", array()), "html", null, true);
                echo "</label>


                            <input type=\"radio\" name=\"_wcml_schedule[";
                // line 68
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                   value=\"1\"
                                   id=\"wcml_schedule_manually[";
                // line 70
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\"
                                   class=\"wcml_schedule_input\" ";
                // line 71
                echo $this->getAttribute($context["currency"], "schedule_man_checked", array());
                echo " />
                            <label for=\"wcml_schedule_manually[";
                // line 72
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "currency_code", array()), "html", null, true);
                echo "]";
                echo twig_escape_filter($this->env, (isset($context["html_id"]) ? $context["html_id"] : null), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "set_dates", array()), "html", null, true);
                echo "
                                <span class=\"block_actions\">(
                                    <a href=\"\" class=\"wcml_schedule_manually_block_show\">";
                // line 74
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "schedule", array()), "html", null, true);
                echo "</a>
                                    <a href=\"\" class=\"wcml_schedule_manually_block_hide\">";
                // line 75
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "collapse", array()), "html", null, true);
                echo "</a>
                                )</span>
                            </label>

                            <div class=\"wcml_schedule_dates\">
                                <input type=\"text\" class=\"short custom_sale_price_dates_from\"
                                       name=\"_custom";
                // line 81
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    echo "_variation";
                }
                echo "_sale_price_dates_from";
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       id=\"_custom_sale_price_dates_from";
                // line 82
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       value=\"";
                // line 83
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "sale_price_dates_from", array()));
                echo "\"
                                       placeholder=\"";
                // line 84
                echo $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "from", array());
                echo " YYYY-MM-DD\"
                                       maxlength=\"10\" pattern=\"[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])\" />

                                <input type=\"text\" class=\"short custom_sale_price_dates_to\"
                                       name=\"_custom";
                // line 88
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    echo "_variation";
                }
                echo "_sale_price_dates_to";
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       id=\"_custom_sale_price_dates_to";
                // line 89
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "custom_id", array()), "html", null, true);
                echo "\"
                                       value=\"";
                // line 90
                echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "sale_price_dates_to", array()));
                echo "\"
                                       placeholder=\"";
                // line 91
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
            // line 99
            echo "        </div>

        <div class=\"wcml_automaticaly_prices_block\">

            ";
            // line 103
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["currencies"]) ? $context["currencies"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["currency"]) {
                // line 104
                echo "                <label>";
                echo $this->getAttribute($context["currency"], "currency_format", array());
                echo "</label>

                ";
                // line 106
                if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
                    // line 107
                    echo "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "readonly_price", array()));
                    foreach ($context['_seq'] as $context["key"] => $context["readonly_price"]) {
                        // line 108
                        echo "                        <p>
                            <label>";
                        // line 109
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), $context["key"], array(), "array"), "html", null, true);
                        echo " ( ";
                        echo $this->getAttribute($context["currency"], "currency_symbol", array());
                        echo " )</label>
                            <input type=\"text\"
                                   name=\"_readonly";
                        // line 111
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\"
                                   class=\"wc_input_price short\"
                                   value=\"";
                        // line 113
                        echo twig_escape_filter($this->env, $context["readonly_price"]);
                        echo "\"
                                   step=\"any\" min=\"0\" readonly = \"readonly\"
                                   rel=\"";
                        // line 115
                        echo twig_escape_filter($this->env, $this->getAttribute($context["currency"], "rate", array()));
                        echo "\" />
                        </p>
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['key'], $context['readonly_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 118
                    echo "                ";
                } else {
                    // line 119
                    echo "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["currency"], "readonly_html", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["readonly_html_price"]) {
                        // line 120
                        echo "                        ";
                        echo $context["readonly_html_price"];
                        echo "
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['readonly_html_price'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 122
                    echo "                ";
                }
                // line 123
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['currency'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 124
            echo "        </div>
    ";
        }
        // line 126
        echo "
    ";
        // line 127
        if (twig_test_empty((isset($context["is_variation"]) ? $context["is_variation"] : null))) {
            // line 128
            echo "        <div class=\"wcml_price_error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "enter_price", array()), "html", null, true);
            echo "</div>
    ";
        }
        // line 130
        echo "</div>

";
        // line 132
        if ((isset($context["is_variation"]) ? $context["is_variation"] : null)) {
            // line 133
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
        return array (  422 => 133,  420 => 132,  416 => 130,  410 => 128,  408 => 127,  405 => 126,  401 => 124,  395 => 123,  392 => 122,  383 => 120,  378 => 119,  375 => 118,  366 => 115,  361 => 113,  356 => 111,  349 => 109,  346 => 108,  341 => 107,  339 => 106,  333 => 104,  329 => 103,  323 => 99,  309 => 91,  305 => 90,  301 => 89,  293 => 88,  286 => 84,  282 => 83,  278 => 82,  270 => 81,  261 => 75,  257 => 74,  248 => 72,  244 => 71,  238 => 70,  231 => 68,  221 => 65,  217 => 64,  210 => 62,  204 => 61,  198 => 58,  194 => 56,  191 => 55,  182 => 53,  177 => 52,  174 => 51,  165 => 48,  161 => 47,  156 => 46,  149 => 44,  146 => 43,  141 => 42,  139 => 41,  136 => 40,  130 => 38,  128 => 37,  122 => 34,  118 => 32,  114 => 31,  108 => 30,  101 => 26,  93 => 25,  86 => 23,  78 => 22,  70 => 17,  64 => 16,  58 => 15,  52 => 14,  44 => 13,  40 => 11,  34 => 8,  31 => 7,  29 => 6,  25 => 4,  21 => 2,  19 => 1,);
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
