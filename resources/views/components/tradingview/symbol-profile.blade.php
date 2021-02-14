@props(['stock' => null])

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-profile.js" async>
  {
  "symbol": "{{ $stock->symbol }}",
  "width": "100%",
  "height": "auto",
  "colorTheme": "light",
  "isTransparent": true,
  "locale": "en"
}
  </script>
</div>
<!-- TradingView Widget END -->
