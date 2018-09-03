{extends file="main.tpl"}
{block name=resources}
    <link rel="stylesheet" href="{$conf->app_url}/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

    <script>
      window.onload = function () {

      var chart = new CanvasJS.Chart("chartContainer", {
      	animationEnabled: true,

      	axisX:{
      		interval: 1
      	},
      	axisY2:{
      		interlacedColor: "rgba(1,77,101,.2)",
      		gridColor: "rgba(1,77,101,.1)",
      		title: "Amount of played games"
      	},
      	data: [{
      		type: "bar",
      		name: "companies",
      		axisYType: "secondary",
      		color: "#014D65",
      		dataPoints: [
            {foreach $trackerList as $t}
                {strip}
          			   { y: {$t['amount']}, label: "{$t['login']}" },
                {/strip}
            {/foreach}
      		]
      	}]
      });
      chart.render();

      }
    </script>
    
{/block}

{block name=bottom}
    {if $partyName != null}
        Party: {$partyName}
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    {/if}
{/block}
