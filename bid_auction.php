<?php

declare(strict_types=1);

class BidAuction {
    private ?int $bestAd = null;
    private ?float $highestBid = null;
    private ?float $secondHighestBid = null;

    public function loadCSV(string $filename): void {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new RuntimeException("File not found or not readable.");
        }

        $handle = fopen($filename, "r");
        if (!$handle) {
            throw new RuntimeException("Failed to open file.");
        }

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) !== 2 || !is_numeric($data[1])) {
                throw new InvalidArgumentException("Invalid CSV format. Each row must contain two values: ad_id and numeric bid.");
            }
            
            $adId = (int) $data[0];
            $bid = (float) $data[1];
            
            if ($this->highestBid === null || $bid > $this->highestBid) {
                $this->secondHighestBid = $this->highestBid;
                $this->highestBid = $bid;
                $this->bestAd = $adId;
            } elseif ($this->secondHighestBid === null || $bid > $this->secondHighestBid) {
                $this->secondHighestBid = $bid;
            }
        }

        fclose($handle);
    }

    public function getWinningAd(): array {
        if ($this->bestAd === null || $this->secondHighestBid === null) {
            throw new UnderflowException("Not enough bids to determine a winner.");
        }

        return [$this->bestAd, $this->secondHighestBid];
    }
}

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    if ($argc !== 2) {
        fwrite(STDERR, "Usage: php bid_auction.php <filename>\n");
        exit(1);
    }

    try {
        $auction = new BidAuction();
        $auction->loadCSV($argv[1]);
        [$bestAd, $secondBid] = $auction->getWinningAd();
        echo "$bestAd, $secondBid\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
        exit(1);
    }
}
