<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------|
| Test Case                                                                |
|--------------------------------------------------------------------------|
|
| The closure you provide to your test functions is bound to the specific
| test case class. By default, all of the tests will run within the
| TestCase class so you have access to all of the Laravel testing helpers.
|
*/

uses(
    TestCase::class,
    RefreshDatabase::class,
)->in('Feature');
