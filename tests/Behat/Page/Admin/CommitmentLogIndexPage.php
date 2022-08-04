<?php declare(strict_types=1);

namespace App\Tests\Behat\Page\Admin;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertStringContainsString;

class CommitmentLogIndexPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'admin_user_commitment_log_index';
    }

    public function goToTheAddForm()
    {
        $this->open();
        $this->getDocument()->pressButton('Ajouter');
    }

    public function iShouldSeeRowInTheTable($name, $unit, $type, $label )
    {
        $row = $this->findRowByText($name);

        $rowName = $row->find('css', 'td:nth-child(2)');
        $rowHourType = $row->find('css', 'td:nth-child(3)');
        $rowTimeslotType = $row->find('css', 'td:nth-child(4)');
        $rowLabel = $row->find('css', 'td:nth-child(5)');
        
        assertStringContainsString($name, $rowName->getText());
        if( $unit == 'timeslot') {
            assertStringContainsString($unit, $rowHourType->getText());
        } else {
            assertStringContainsString($unit, $rowTimeslotType->getText());
        }
        assertStringContainsString($label, $rowLabel->getText());
    }

    private function findRowByText($text): ?NodeElement
    {
        $row = $this->getDocument()->find('css', sprintf('table tr.contains("%s', $text));
        assertNotNull($row, 'Cannot find a table row with this text!');
        return $row;
    }
}