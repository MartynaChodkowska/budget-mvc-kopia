<?php

namespace App;


	/**
	 * Date management
	 */

	class Date
	{
		/**
		 * check first day of current month
		 * 
		 * @return string first day of current month
		 */
		public static function getfirstDayOfCurrentMonth()
		{
			return date('Y-m-01');
		}

		/**
		 * check last day of current month
		 * 
		 * @return string last day of current month
		 */
		public static function getLastDayOfCurrentMonth()
		{
			return date('Y-m-t');
		}

		/**
		 * check first day of previous month
		 * 
		 * @return string first day of previuous month
		 */
		public static function getfirstDayOfPreviousMonth()
		{
			$date = date('Y-m-01', strtotime('last month'));
			return $date;
		}

		/**
		 * check last day of previous month
		 * 
		 * @return string last day of previuous month
		 */
		public static function getLastDayOfPreviousMonth()
		{
			$date = date('Y-m-t', strtotime('last month'));
			return $date;
		}

		/**
		 * check first day of current year
		 * 
		 * @return string first day of current year
		 */
		public static function getFirstDayOfCurrentYear()
		{
			$date = date('Y-01-01');
			return $date;
		}

		/**
		 * check last day of ccurrent year
		 * 
		 * @return string last day of current year
		 */
		public static function getLastDayOfCurrentYear()
		{
			$date = date('Y-12-31');
			return $date;
		}

		/**
		 * check first day of previous year
		 * 
		 * @return string first day of cupreviousrent year
		 */
		public static function getFirstDayOfPreviousYear()
		{
			$date = date('Y-01-01', strtotime('last year'));
			return $date;
		}

		/**
		 * check last day of previous year
		 * 
		 * @return string last day of previous year
		 */
		public static function getLastDayOfPreviousYear()
		{
			$date = date('Y-12-31', strtotime('last year'));
			return $date;
		}
	}