<?php 
// purpose To implement a publish/subscribe behaviour to an object, whenever a "Subject" object changes its state, the attached "Observers" will be notified. It is used to shorten the amount of coupled objects and uses loose coupling instead.

namespace DesignPatterns\Behavioral\Observer;
/**
 * User implements the observed object (called Subject), it maintains a list of observers and sends notifications to
 * them in case changes are made on the User object
 */
class User implements \SplSubject
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var \SplObjectStorage
     */
    private $observers;
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }
    public function changeEmail(string $email)
    {
        $this->email = $email;
        $this->notify();
    }
    public function notify()
    {
        /** @var \SplObserver $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}


namespace DesignPatterns\Behavioral\Observer;
class UserObserver implements \SplObserver
{
    /**
     * @var User[]
     */
    private $changedUsers = [];
    /**
     * It is called by the Subject, usually by SplSubject::notify()
     *
     * @param \SplSubject $subject
     */
    public function update(\SplSubject $subject)
    {
        $this->changedUsers[] = clone $subject;
    }
    /**
     * @return User[]
     */
    public function getChangedUsers(): array
    {
        return $this->changedUsers;
    }
}



namespace DesignPatterns\Behavioral\Observer\Tests;
use DesignPatterns\Behavioral\Observer\User;
use DesignPatterns\Behavioral\Observer\UserObserver;
use PHPUnit\Framework\TestCase;
class ObserverTest extends TestCase
{
    public function testChangeInUserLeadsToUserObserverBeingNotified()
    {
        $observer = new UserObserver();
        $user = new User();
        $user->attach($observer);
        $user->changeEmail('foo@bar.com');
        $this->assertCount(1, $observer->getChangedUsers());
    }
}