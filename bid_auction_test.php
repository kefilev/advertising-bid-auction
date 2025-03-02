<?php

require_once 'bid_auction.php';

function runTest(string $testName, callable $testFunction): void {
    try {
        $testFunction();
        echo "[✔] PASS: $testName\n";
    } catch (Throwable $e) {
        echo "[✖] FAIL: $testName - " . $e->getMessage() . "\n";
    }
}

function testWinningAd() {
    $auction = new BidAuction();
    
    // Create a temporary CSV file for testing
    $testFile = 'test_bids.csv';
    file_put_contents($testFile, "1, 0.5\n2, 33\n3, 12\n4, 33.5\n");

    $auction->loadCSV($testFile);
    $result = $auction->getWinningAd();

    if ($result != [4, 33]) {
        throw new Exception("Expected [4, 33], got [" . implode(", ", $result) . "]");
    }

    unlink($testFile); // Clean up the test file
}

function testInvalidFile() {
    $auction = new BidAuction();

    try {
        $auction->loadCSV('nonexistent.csv');
    } catch (RuntimeException $e) {
        return; // Test passed
    }
    
    throw new Exception("Expected RuntimeException for missing file.");
}

function testInvalidCSVFormat() {
    $auction = new BidAuction();
    
    // Create an invalid CSV file
    $testFile = 'invalid_bids.csv';
    file_put_contents($testFile, "invalid, data\n");

    try {
        $auction->loadCSV($testFile);
    } catch (InvalidArgumentException $e) {
        unlink($testFile); // Clean up
        return; // Test passed
    }

    unlink($testFile);
    throw new Exception("Expected InvalidArgumentException for incorrect CSV format.");
}

// Run tests
echo "Running tests...\n";
runTest("Valid Bids File", 'testWinningAd');
runTest("Missing File", 'testInvalidFile');
runTest("Invalid CSV Format", 'testInvalidCSVFormat');

echo "Tests completed.\n";
