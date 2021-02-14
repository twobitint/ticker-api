@props(['stock' => null])

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-financials.js" async>
  {
  "symbol": "{{ $stock->symbol }}",
  "colorTheme": "light",
  "isTransparent": true,
  "largeChartUrl": "",
  "displayMode": "regular",
  "width": "100%",
  "height": 800,
  "locale": "en"
}
  </script>
</div>
<!-- TradingView Widget END -->
