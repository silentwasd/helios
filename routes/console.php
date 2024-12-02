<?php

Schedule::command('update:server-status')->everyMinute();
Schedule::command('update:program-status')->everyMinute();
