# Bid Auction

In advertising bid auctions, there is a mechanism that allows the best offer to win with the price of the second-best bid.

This is a simple PHP script to determine the winning ad and the second-highest bid from a CSV file containing ad bids.

## Requirements
- PHP 8.2 or later

## Usage
Run the script from the command line, providing the path to a CSV file (in this example it should return 4, 33):

```sh
php bid_auction.php bids.csv
```

To test with the example CSV file (test.csv) with the max number of 10000 rows. The resilt should be 57466, 999.98:
```sh
php bid_auction.php test.csv
```

### CSV Format
The CSV file should contain two columns: `ad_id` (integer) and `bid` (float), for example:
```
1, 0.5
2, 33
3, 12
4, 33.5
```
### Expected Output
The script outputs the `ad_id` with the highest bid and the second-highest bid:
```
4, 33
```

## Running Tests
A test script (`bid_auction_test.php`) is provided to verify the implementation. To run the tests:

```sh
php bid_auction_test.php
```

### Expected Test Output
If all tests pass, you should see:
```
Running tests...
[✔] PASS: Valid Bids File
[✔] PASS: Missing File
[✔] PASS: Invalid CSV Format
Tests completed.
```

The test script automatically generates test cases and validates the output.

## Notes
- Ensure the CSV file is correctly formatted.
- The script only keeps track of the top two highest bids for efficiency.
- The test script creates temporary files for testing and cleans them up after execution.

Enjoy! 🚀
