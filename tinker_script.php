<?php
echo json_encode(\App\Models\Donation::orderBy('donation_date', 'desc')->take(5)->get());
