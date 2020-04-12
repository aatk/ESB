<?php

$SERVERNAME = $_SERVER["HTTP_HOST"];

?>

<html>
<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>

        var x = window.location;

        $(document).ready(function ()
        {

            $(".pages").hide();
            $(x.hash).show();
            //$.find(x.hash).parent.addClass("active");

            $(".nav-link").on("click", function () {
                //
                var href = $(this).attr("href");
                $(".pages").hide();
                //$.find(".active").removeClass("active");

                $(href).show();
                //$.find(href).parent.addClass("active");

            });

        })
        
    </script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">I4B.RU</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#page1">API</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#page2">CMS</a>
            </li>
        </ul>

<!--        <form class="form-inline mt-2 mt-md-0">-->
<!--            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">-->
<!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
<!--        </form>-->

    </div>
</nav>



<main role="main" class="container">
    <div class="jumbotron">

        <div class="pages" id="page1">

            <h1>GET STARTED</h1>

            <h1>REST API simple development</h1>

            <p>Query API</p>
            <p>
                Create a new php class in the format YOUNAME.class.php<br/>
                save the file in the "client" or "private" folders<br/>
                <br/>
                The content of the new class must be:<br/>
            </p>

            <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span>
            &nbsp;
            <span style="color: #000000; font-weight: bold;">class</span> YOUCLASS <span style="color: #000000; font-weight: bold;">extends</span> ex_class
            <span style="color: #009900;">&#123;</span>
                <span style="color: #000000; font-weight: bold;">private</span> <span style="color: #000088;">$metod</span><span style="color: #339933;">;</span>
            &nbsp;
                <span style="color: #000000; font-weight: bold;">public</span> <span style="color: #000000; font-weight: bold;">function</span> __construct<span style="color: #009900;">&#40;</span><span style="color: #000088;">$metod</span> <span style="color: #339933;">=</span> <span style="color: #0000ff;">&quot;&quot;</span><span style="color: #009900;">&#41;</span>
                <span style="color: #009900;">&#123;</span>
                    <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">metod</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$metod</span><span style="color: #339933;">;</span>
                    parent<span style="color: #339933;">::</span>__construct<span style="color: #009900;">&#40;</span><span style="color: #009900; font-weight: bold;">null</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
                <span style="color: #009900;">&#125;</span>
            &nbsp;
                <span style="color: #000000; font-weight: bold;">public</span> <span style="color: #000000; font-weight: bold;">function</span> CreateDB<span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span>
                <span style="color: #009900;">&#123;</span>
                    <span style="color: #666666; font-style: italic;">//this code runs when installing or reinstalling the system core</span>
                    <span style="color: #666666; font-style: italic;">//the framework is used for working with the database made.in (documentation https://medoo.in/)</span>
            &nbsp;
                    <span style="color: #666666; font-style: italic;">/* Description of tables for the class */</span>
                    <span style="color: #000088;">$info_table</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;test_table&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #009900;">&#91;</span>
                        <span style="color: #0000ff;">&quot;id&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">'type'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'int(11)'</span><span style="color: #339933;">,</span> <span style="color: #0000ff;">'null'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'NOT NULL'</span><span style="color: #339933;">,</span> <span style="color: #0000ff;">'inc'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900; font-weight: bold;">true</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
                        <span style="color: #0000ff;">&quot;varchar&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">'type'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'varchar(150)'</span><span style="color: #339933;">,</span> <span style="color: #0000ff;">'null'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'NOT NULL'</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
                        <span style="color: #0000ff;">&quot;int&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">'type'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'int(15)'</span><span style="color: #339933;">,</span> <span style="color: #0000ff;">'null'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'NOT NULL'</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
                        <span style="color: #0000ff;">&quot;bool&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">'type'</span> <span style="color: #339933;">=&gt;</span> <span style="color: #0000ff;">'bool'</span><span style="color: #009900;">&#93;</span>
                    <span style="color: #009900;">&#93;</span><span style="color: #339933;">;</span>
            &nbsp;
                    <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">create</span><span style="color: #009900;">&#40;</span><span style="color: #0000ff;">'mysql'</span><span style="color: #339933;">,</span> <span style="color: #000088;">$info_table</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
                <span style="color: #009900;">&#125;</span>
            &nbsp;
                <span style="color: #000000; font-weight: bold;">public</span> <span style="color: #000000; font-weight: bold;">function</span> InstallModule<span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span>
                <span style="color: #009900;">&#123;</span>
                    <span style="color: #666666; font-style: italic;">//this code runs when installing or reinstalling the system core</span>
                <span style="color: #009900;">&#125;</span>
            &nbsp;
                <span style="color: #000000; font-weight: bold;">public</span> <span style="color: #000000; font-weight: bold;">function</span> Init<span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#41;</span>
                <span style="color: #009900;">&#123;</span>
                    <span style="color: #000088;">$result</span> <span style="color: #339933;">=</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;result&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900; font-weight: bold;">false</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;error&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #0000ff;">&quot;Error function call&quot;</span><span style="color: #339933;">;</span>
            &nbsp;
                    <span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">metod</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;POST&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                        <span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demopost&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #000088;">$result</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">demoquery</span><span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
                        <span style="color: #009900;">&#125;</span> <span style="color: #b1b100;">elseif</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demopost2&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #666666; font-style: italic;">//$result = $this-&gt;demopost2($param);</span>
                        <span style="color: #009900;">&#125;</span>
            &nbsp;
                    <span style="color: #009900;">&#125;</span> <span style="color: #b1b100;">elseif</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">metod</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;PATCH&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                        <span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demopatch&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #000088;">$result</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">demoquery</span><span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
                        <span style="color: #009900;">&#125;</span> <span style="color: #b1b100;">elseif</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demopatch2&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #666666; font-style: italic;">//$result = $this-&gt;demoquery($param);</span>
                        <span style="color: #009900;">&#125;</span>
            &nbsp;
                    <span style="color: #009900;">&#125;</span> <span style="color: #b1b100;">elseif</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">metod</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;GET&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                        <span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demoget&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #000088;">$result</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">demoquery</span><span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
                        <span style="color: #009900;">&#125;</span> <span style="color: #b1b100;">elseif</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$param</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">0</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> <span style="color: #0000ff;">&quot;demoget2&quot;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
                            <span style="color: #666666; font-style: italic;">//$result = $this-&gt;demoquery($param);</span>
                        <span style="color: #009900;">&#125;</span>
                    <span style="color: #009900;">&#125;</span>
            &nbsp;
                    <span style="color: #b1b100;">return</span> <span style="color: #000088;">$result</span><span style="color: #339933;">;</span>
                <span style="color: #009900;">&#125;</span>
            &nbsp;
            &nbsp;
                <span style="color: #000000; font-weight: bold;">private</span> <span style="color: #000000; font-weight: bold;">function</span> demoquery<span style="color: #009900;">&#40;</span><span style="color: #000088;">$Params</span><span style="color: #009900;">&#41;</span>
                <span style="color: #009900;">&#123;</span>
                    <span style="color: #000088;">$result</span> <span style="color: #339933;">=</span> <span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;result&quot;</span> <span style="color: #339933;">=&gt;</span> <span style="color: #009900; font-weight: bold;">false</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">;</span>    <span style="color: #666666; font-style: italic;">//if set to FALSE the request returns the status 500 and the passed data</span>
            &nbsp;
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;Params&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$Params</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;GET&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">GET</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;POST&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">POST</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;REQUEST&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">REQUEST</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;SERVER&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">SERVER</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;FILES&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">FILES</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;URI&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">URI</span><span style="color: #339933;">;</span>
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;phpinput&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">phpinput</span><span style="color: #339933;">;</span>
            &nbsp;
                    <span style="color: #000088;">$result</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;result&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">=</span> <span style="color: #009900; font-weight: bold;">true</span><span style="color: #339933;">;</span>   <span style="color: #666666; font-style: italic;">//if set to TRUE the request returns the status 200 and the passed data</span>
            &nbsp;
                    <span style="color: #b1b100;">return</span> <span style="color: #000088;">$result</span><span style="color: #339933;">;</span>
                <span style="color: #009900;">&#125;</span>
            <span style="color: #009900;">&#125;</span>
            &nbsp;</pre>

            <h2>QUERY</h2>

            <h3><a target="_blank" href="http://{{SERVERNAME}}/YOUCLASS/demoget/param1/param2?param3=value3&amp;param4=value4">http://{{SERVERNAME}}/YOUCLASS/demoget/param1/param2?param3=value3&amp;param4=value4</a></h3>

            <h2>RESULT</h2>

            <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span>
              <span style="color: #0000ff;">&quot;result&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900; font-weight: bold;">true</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;Params&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#91;</span>
                <span style="color: #0000ff;">&quot;demoget&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param1&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param2&quot;</span>
              <span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;GET&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#123;</span>
                <span style="color: #0000ff;">&quot;q&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;YOUCLASS\/demoget\/param1\/param2&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param3&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;value3&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param4&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;value4&quot;</span>
              <span style="color: #009900;">&#125;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;POST&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#91;</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;REQUEST&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#123;</span>
                <span style="color: #0000ff;">&quot;param3&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;value3&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param4&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;value4&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;PHPSESSID&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;793ffb561486704b1dfdd1283479e90d&quot;</span>
              <span style="color: #009900;">&#125;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;SERVER&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#123;</span>
                <span style="color: #0000ff;">&quot;REDIRECT_STATUS&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;200&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;REQUEST_METHOD&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;GET&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;argv&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#91;</span>
                  <span style="color: #0000ff;">&quot;q=YOUCLASS\/demoget\/param1\/param2&amp;param3=value3&amp;param4=value4&quot;</span>
                <span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;argc&quot;</span><span style="color: #339933;">:</span> <span style="color: #cc66cc;">1</span>
              <span style="color: #009900;">&#125;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;FILES&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#91;</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;URI&quot;</span><span style="color: #339933;">:</span> <span style="color: #009900;">&#91;</span>
                <span style="color: #0000ff;">&quot;&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;YOUCLASS&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;demoget&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param1&quot;</span><span style="color: #339933;">,</span>
                <span style="color: #0000ff;">&quot;param2?param3=value3&amp;param4=value4&quot;</span>
              <span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span>
              <span style="color: #0000ff;">&quot;phpinput&quot;</span><span style="color: #339933;">:</span> <span style="color: #0000ff;">&quot;&quot;</span>
            <span style="color: #009900;">&#125;</span></pre>

        </div>
        <div class="pages" id="page2">
            <h1>CMS simple formatting</h1>

            <p>
                I4B CMS - позволяет структурировать и работать со страницами html, создавать контент и управлять им.
                Вы можеете хранить страницы как классически файлами на диске, так и хрнить их в БД.
                За работу с контентом CMS достаточно размещать свои файлы в папке "pages", за работу с контентом отвечает класс Pages.class.php
            </p>

            <p>
                ВНИМАНИЕ!!! Если имя файла и любого класса будут идентичны, то приоритетной выдачей будет разультат работы класса!
            </p>

            <div class="helpcontent">
                <h1>Дополнения/Упращения</h1>
                <p>Все поддерживаемые CMS упрощения должны быть обрамлены в двойные фигурные скобки {{  и  }}</p>
                <p>Если это снипплет, то после открывающих скобок "{{" должен стоять $ - он говорит о том, что дальше будет указан путь до сниплета</p>
                <br/>
                <h1>Переменные и массивы</h1>
                <p>Переменные требуется использовать без знака "$" (доллар)</p>
                <p>Доступ к значению массива доступен через "." (точку) чем глубже получение данных тем больше (точек).</p>
            </div>


            <div class="helpcontent">
            <h1>Sniplets</h1>
            <p>
                Сниплеты позволяют подгружать на страницу одинаковый контекст, примером может быть:
                Меню или Корзина, которые отображаются на каждой странице.
                Различные вставки, такие как ссылки на соц.сети, статусы задач или заказов.
            </p>
            <p>
                Сниплеты также можно вызывать динамически с помощью Ajax на страницах, которые могут возвращать какой либо динамический контент.
                Примером вызова такого сниплета является загрузка страниц с пигинацией, или динамической загрузки контента.
            </p>

            <h2>Snipplet path</h2>
            <img src="img/snipplet_path_demo.jpg"/>

            <h2>Snipplet code</h2>
            <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span>
<span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #990000;">empty</span><span style="color: #009900;">&#40;</span><span style="color: #000088;">$Params</span><span style="color: #009900;">&#41;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
    <span style="color: #666666; font-style: italic;">//if ajax load</span>
    <span style="color: #000088;">$Params</span> <span style="color: #339933;">=</span> <span style="color: #009900;">&#91;</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">URI</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">4</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">,</span> <span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">URI</span><span style="color: #009900;">&#91;</span><span style="color: #cc66cc;">5</span><span style="color: #009900;">&#93;</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">;</span>
<span style="color: #009900;">&#125;</span>
<span style="color: #b1b100;">echo</span> <span style="color: #990000;">json_encode</span><span style="color: #009900;">&#40;</span><span style="color: #000088;">$Params</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #000000; font-weight: bold;">?&gt;</span></pre>

            <h2>Snipplet query in page</h2>
            <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #000088;">$DEMO</span><span style="color: #339933;">.</span>snipplet<span style="color: #339933;">.</span>demo1<span style="color: #009900;">&#40;</span>argument1<span style="color: #339933;">,</span>argument2<span style="color: #009900;">&#41;</span><span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span></pre>

            <?php $argument1 = "123"; $argument2 = "qwe";?>
            <h2>Snipplet ajax query</h2>
            <pre class="javascript" style="font-family:monospace;"><span style="color: #339933;">&lt;</span>script<span style="color: #339933;">&gt;</span>
    $<span style="color: #009900;">&#40;</span>document<span style="color: #009900;">&#41;</span>.<span style="color: #660066;">ready</span><span style="color: #009900;">&#40;</span><span style="color: #000066; font-weight: bold;">function</span> <span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span>
        $.<span style="color: #000066; font-weight: bold;">get</span><span style="color: #009900;">&#40;</span><span style="color: #3366CC;">&quot;DEMO/snipplet/demo1/123/qwe&quot;</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
    <span style="color: #009900;">&#125;</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #339933;">&lt;/</span>script<span style="color: #339933;">&gt;</span></pre>


            <h2>Snipplet RESULT</h2>
            {{$DEMO.snipplet.demo1(argument1,argument2)}}

            </div>

            <div class="helpcontent">
                <h1>if</h1>
                <p>Упрощенный оператор if позволяет сравнивать значения переменных, аналогично оператору PHP</p>
                <img scr=""/>

    <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #990000;">array</span><span style="color: #339933;">.</span>argument <span style="color: #339933;">==</span> argument1<span style="color: #009900;">&#41;</span><span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span>
<span style="color: #339933;">&lt;</span>a<span style="color: #339933;">&gt;</span>Button<span style="color: #339933;">&lt;/</span>a<span style="color: #339933;">&gt;</span>
<span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #b1b100;">endif</span><span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span></pre>

                <p>similarly</p>

    <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #b1b100;">if</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$array</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;argument&quot;</span><span style="color: #009900;">&#93;</span> <span style="color: #339933;">==</span> argument1<span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span>
&lt;a&gt;Button&lt;/a&gt;
<span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #009900;">&#125;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span>
&nbsp;</pre>

            </div>

            <div class="helpcontent">
                <h1>foreach</h1>
                <p>Упрощенный оператор foreach позволяет циклически пройти по массиву, аналогично оператору PHP</p>
                <img scr=""/>
            <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #b1b100;">foreach</span> <span style="color: #009900;">&#40;</span><span style="color: #990000;">array</span> <span style="color: #b1b100;">as</span> items<span style="color: #009900;">&#41;</span><span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span>
            <span style="color: #339933;">&lt;</span>a<span style="color: #339933;">&gt;</span>Button<span style="color: #339933;">&lt;/</span>a<span style="color: #339933;">&gt;</span>
<span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #b1b100;">endforeach</span><span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span></pre>

                <p>similarly</p>

            <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #b1b100;">foreach</span> <span style="color: #009900;">&#40;</span><span style="color: #000088;">$array</span> <span style="color: #b1b100;">as</span> <span style="color: #000088;">$items</span><span style="color: #009900;">&#41;</span> <span style="color: #009900;">&#123;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span>
&lt;a&gt;Button&lt;/a&gt;
<span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #009900;">&#125;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span>
&nbsp;</pre>
            </div>

            <div class="helpcontent">
                <h1>Variable</h1>
                <p>Как было описано в начале, для отображения значения переменной достаточно указать её в фигурных скобках "{{" и "}}"</p>
                <img scr=""/>
                <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span>variable<span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span></pre>

                <p>similarly</p>

                <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #b1b100;">echo</span> <span style="color: #000088;">$variable</span><span style="color: #339933;">;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span>           </pre>
            </div>

            <div class="helpcontent">
            <h1>Array</h1>
                <p>Как было описано в начале, для доступа к значению массива достаточно указать через точку путь к переменной, в фигурных скобках "{{" и "}}"</p>
                <img scr=""/>
                <pre class="php" style="font-family:monospace;"><span style="color: #009900;">&#123;</span><span style="color: #009900;">&#123;</span><span style="color: #990000;">array</span><span style="color: #339933;">.</span>name<span style="color: #339933;">.</span>name2<span style="color: #339933;">.</span>name3<span style="color: #009900;">&#125;</span><span style="color: #009900;">&#125;</span></pre>

                <p>similarly</p>
                <pre class="php" style="font-family:monospace;"><span style="color: #000000; font-weight: bold;">&lt;?php</span> <span style="color: #b1b100;">echo</span> <span style="color: #000088;">$array</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;name&quot;</span><span style="color: #009900;">&#93;</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;name2&quot;</span><span style="color: #009900;">&#93;</span><span style="color: #009900;">&#91;</span><span style="color: #0000ff;">&quot;name3&quot;</span><span style="color: #009900;">&#93;</span><span style="color: #339933;">;</span> <span style="color: #000000; font-weight: bold;">?&gt;</span></pre>
            </div>
        </div>

    </div>
</main>


</body>
</html>
