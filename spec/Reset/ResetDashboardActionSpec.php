<?php

namespace spec\Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Reset\ResetDashboardAction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResetDashboardActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResetDashboardAction::class);
    }

    function it_deletes_a_few_keys_from_a_global_wp_object(AdminAdapter $admin)
    {
        global $wp_meta_boxes;

        $wp_meta_boxes = [
            'dashboard' => [
                'normal' => [
                    'core' => [
                        'dashboard_right_now' => 1,
                        'dashboard_activity' => 1,
                    ]
                ],
                'side' => [
                    'core' => [
                        'dashboard_quick_press' => 1,
                        'dashboard_primary' => 1,
                    ]
                ],
            ]
        ];

        $this->call($admin, []);

        if ($wp_meta_boxes !== [
            'dashboard' => [
                'normal' => [
                    'core' => []
                ],
                'side' => [
                    'core' => []
                ],
            ]
        ]) {
            throw new Exception("Didn't correctly remove keys from the global");
        }
    }
}
