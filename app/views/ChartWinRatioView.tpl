{extends file="main.tpl"}
{block name=resources}
    <link rel="stylesheet" href="{$conf->app_url}/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

    <script>
      window.onload = function () {

      var chart = new CanvasJS.Chart("chartContainer", {
      	animationEnabled: true,
      	theme: "light2",
      	title:{
      		text: "Win ratio"
      	},
      	axisY: {
      		title: "Percentage of wins (%)"
      	},
      	data: [{
      		type: "column",
          dataPoints: [
            {foreach $data as $d}
                {strip}
          			   { y: {$d['win_ratio']*100}, label: "{$d['login']}" },
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
