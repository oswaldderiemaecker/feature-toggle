<?php

require_once __DIR__ . '/vendor/autoload.php';
use Opensoft\Rollout\Rollout;
use Opensoft\Rollout\Storage\ArrayStorage;
use Opensoft\Rollout\RolloutUserInterface;
use Lijinma\Color;

/**
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class RolloutUser implements RolloutUserInterface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getRolloutIdentifier()
    {
        return $this->id;
    }
}

$rollout = new Rollout(new ArrayStorage());

echo Color::GREEN . "This is the main program\n\n";
echo Color::GREEN . "Activating the 'It run Behat scenarios' Feature to only one specific user Id 24\n\n";

$rollout->activate('it-run-behat');
$feature = $rollout->get('it-run-behat');
echo 'Feature Name:' . $feature->getName() . PHP_EOL;

echo Color::YELLOW . "\n+ Activating three users Id (42, 4242 and 24)\n";

$rollout->activateUser('it-run-behat', new RolloutUser(42));
$rollout->activateUser('it-run-behat', new RolloutUser(4242));
$rollout->activateUser('it-run-behat', new RolloutUser(24));

echo Color::YELLOW . "- Deactivating users 42 and 4242\n";
$rollout->deactivateUser('it-run-behat', new RolloutUser(42));
$rollout->deactivateUser('it-run-behat', new RolloutUser('4242'));

echo Color::RED . "\nThe Feature is not active for User 42 and 4242, neither any others\n";
echo '42: ';
var_dump($rollout->isActive('it-run-behat', new RolloutUser(42)));
echo '4242: ';
var_dump($rollout->isActive('it-run-behat', new RolloutUser(4242)));
echo '5000: ';
var_dump($rollout->isActive('it-run-behat', new RolloutUser(5000)));

if ($rollout->isActive('it-run-behat', new RolloutUser(24))) {
	echo Color::GREEN . "\nUser 24 can run Behat scenarios\n\n" . Color::WHITE;
}

echo "Removing the Feature\n";
$rollout->remove('it-run-behat');
var_dump($rollout->features());
