<?php

/**
 * Class trader
 */
final class trader {

    /**
     *
     */
    public function initRealtimeTrading() {
        $analysis = new _analysis();
        $analysis->doAnalysePairsRecursive(function(array $score_details) {
            /*$trade = new trade($pair);
            $trade->doBuy($pair);
            $trade->doSell($pair);*/
        });
    }

    /**
     *
     */
    public function initSwingTradeAlerts() {
        foreach (pairs::getPairs() as $pair) {
            /**@var _pair $pair */
            echo '<p>Processing ' . $pair->getPairName('/') . '</p>'."\n";
            flush();

            $analysis = new _analysis();
            $analysis->default_pair_data = $this->getSwingTradeData($pair);
            $analysis->doAnalysePair($pair, function(array $score_details) {
                echo '<p style="color:green;font-weight:bold;">Trade details: <pre>' . print_r($score_details, true) . '</pre></p>'."\n";
                flush();
            });
        }
    }

    /**
     * @param \_pair $pair
     *
     * @return array
     */
    private function getSwingTradeData(_pair $pair) {
        $result = [];

        $oanda = new oanda_rest_api();
        if ($response = $oanda->doApiRequest('candles', [
            'instrument' => $pair->getPairName(),
            'granularity' => 'D',
            'count' => 300
        ], 'GET')) {
            if (!empty($response['candles'])) {
                foreach ($response['candles'] as $row) {
                    if ($row['complete']) {
                        $class = new avg_price_data();
                        $class->pair = $pair;
                        $class->time = substr($row['time'], 0, 10);
                        $class->date_time = date('d/m/Y H:i:s', $class->time);
                        $class->open = $row['openBid'];
                        $class->close = $row['closeBid'];
                        $class->high = $row['highBid'];
                        $class->low = $row['lowBid'];
                        $class->volume = $row['volume'];

                        $result[] = $class;
                    } else {
                        echo '<p>Non-complete day skipped...</p>'."\n";
                    }
                }
            }
        }

        return $result;
    }
}