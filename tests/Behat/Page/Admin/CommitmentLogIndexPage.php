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
        return 'admin_commitment_log_index';
    }

    public function goToTheAddForm()
    {
        $this->open();
        $this->getDocument()->clickLink('Ajouter un engagement');
    }

    public function iShouldSeeRowInTheTable($name, $unit, $type, $comment )
    {
        $row = $this->findRowByText($name);

        $rowName = $row->find('css', 'td:nth-child(2)');
        $rowTimeslotType = $row->find('css', 'td:nth-child(3)');
        $rowHourType = $row->find('css', 'td:nth-child(4)');
        $rowComment = $row->find('css', 'td:nth-child(5)');
        
        assertStringContainsString($name, $rowName->getText());
        if( $type == 'hours' || $type == 'hour') {
            assertStringContainsString($unit, $rowHourType->getText());
        } else {
            assertStringContainsString($unit, $rowTimeslotType->getText());
        }
        assertStringContainsString($comment, $rowComment->getText());
    }

    private function findRowByText($text): ?NodeElement
    {
        $row = $this->getDocument()->find('css', sprintf('table tr:contains("%s")', $text));
        
        var_export($row->getText());
        assertNotNull($row, 'Cannot find a table row with this text!');
        return $row;
    }
}