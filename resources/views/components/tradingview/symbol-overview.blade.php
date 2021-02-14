@props(['stock' => null])

<div style="height: 350px;">
  <div class="tradingview-widget-container">
    <div id="tradingview_07d27"></div>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
    new TradingView.MediumWidget(
    {
    "symbols": [
      [
        "{{ $stock->symbol }}|1D"
      ]
    ],
    "chartOnly": true,
    "width": "100%",
    "height": "100%",
    "locale": "en",
    "colorTheme": "light",
    "gridLineColor": "#f0f3fa",
    "trendLineColor": "#2196f3",
    "fontColor": "#787b86",
    "underLineColor": "#e3f2fd",
    "isTransparent": false,
    "autosize": true,
    "container_id": "tradingview_07d27"
  }
    );
    </script>
  </div>
</div>
