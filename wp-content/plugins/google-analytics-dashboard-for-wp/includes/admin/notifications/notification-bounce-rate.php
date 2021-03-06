<?php

/**
 * Add notification when bounce rate is higher than 70%
 * Recurrence: Once weekly
 *
 * @since 7.12.3
 */
final class ExactMetrics_Notification_Bounce_Rate extends ExactMetrics_Notification_Event {

	public $notification_id             = 'exactmetrics_notification_bounce_rate';
	public $notification_interval       = 7; // in days
	public $notification_type           = array( 'basic', 'lite', 'master', 'plus', 'pro' );

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$data                = array();
		$report              = $this->get_report( 'overview', $this->report_start_from, $this->report_end_to );
		$data['bounce_rate'] = isset( $report['data']['infobox']['bounce']['value'] ) ? $report['data']['infobox']['bounce']['value'] : 0;

		if ( ! empty( $data ) && $data['bounce_rate'] > 70 ) {
			$notification['title']   = __( 'Your website bounce rate is higher than 70%', 'google-analytics-dashboard-for-wp' );
			// Translators: Bounce rate notification content
			$notification['content'] = sprintf( __( 'Your website bounce rate is %s. High bounce rates can hurt your site’s conversions rates. A high bounce rate might mean that people aren’t finding what they’re looking for on your site. %sHere%s are some points to remember and steps to follow to get your bounce rates back to manageable levels.', 'google-analytics-dashboard-for-wp' ), $data['bounce_rate'] . '%', '<a href="'. $this->build_external_link('https://www.exactmetrics.com/how-to-reduce-bounce-rate/' ) .'" target="_blank">', '</a>' );
			$notification['btns']    = array(
				"view_report" => array(
					'url'  => $this->get_view_url(),
					'text' => __( 'View Report', 'google-analytics-dashboard-for-wp' )
				),
				"learn_more"  => array(
					'url'  => $this->build_external_link('https://www.exactmetrics.com/how-to-reduce-bounce-rate/' ),
					'text' => __( 'Learn More', 'google-analytics-dashboard-for-wp' )
				),
			);

			return $notification;
		}

		return false;
	}

}

// initialize the class
new ExactMetrics_Notification_Bounce_Rate();
