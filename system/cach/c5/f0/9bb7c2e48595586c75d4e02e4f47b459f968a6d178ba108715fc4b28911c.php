<?php

/* index.twig.html */
class __TwigTemplate_c5f09bb7c2e48595586c75d4e02e4f47b459f968a6d178ba108715fc4b28911c extends Twig_Template
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
        echo "<!DOCTYPE html>
<html>
<head>
\t<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\t
\t<title>Тестовое задание</title>
</head>
<body>
\t";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["variable"]) ? $context["variable"] : null), "html", null, true);
        echo "
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  28 => 8,  19 => 1,);
    }
}
