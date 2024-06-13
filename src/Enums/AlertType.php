<?php

namespace Mayank\Alert\Enums;

enum AlertType: string
{
    case info = 'info';
    case success = 'success';
    case warning = 'warning';
    case failure = 'failure';
}
