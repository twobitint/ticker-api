@props(['stock' => null])

<div class="tradingview-widget-container">
  <div id="tradingview" style="height: 300px;"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
  <script type="text/javascript">
    new TradingView.widget({
      "autosize": true,
      "symbol": "{{ $stock->symbol }}",
      "interval": "5",
      "timezone": "America/New_York",
      "theme": "light",
      "style": "3",
      "locale": "en",
      "toolbar_bg": "#f1f3f6",
      "enable_publishing": false,
      "hide_top_toolbar": true,
      "hide_legend": true,
      "withdateranges": true,
      "range": "1D",
      "save_image": false,
      "calendar": true,
      "container_id": "tradingview"
    });
  </script>
</div>
