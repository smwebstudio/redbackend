#!/bin/sh

# Restart the workers

# Enable the workers
sudo systemctl enable laravel_horizon.service
sudo systemctl enable laravel_schedule.service

# Restart the workers
sudo systemctl restart laravel_horizon.service
sudo systemctl restart laravel_schedule.service
