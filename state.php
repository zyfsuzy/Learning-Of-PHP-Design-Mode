<?php 
//1. defaine
//Encapsulate varying behavior for the same routine based on an object's state. This can be a cleaner way for an object to change its behavior at runtime without resorting to large monolithic conditional statements.

namespace DesignPatterns\Behavioral\State;
class OrderContext
{
    /**
     * @var State
     */
    private $state;
    public static function create(): OrderContext
    {
        $order = new self();
        $order->state = new StateCreated();
        return $order;
    }
    public function setState(State $state)
    {
        $this->state = $state;
    }
    public function proceedToNext()
    {
        $this->state->proceedToNext($this);
    }
    public function toString()
    {
        return $this->state->toString();
    }
}



namespace DesignPatterns\Behavioral\State;
interface State
{
    public function proceedToNext(OrderContext $context);
    public function toString(): string;
}


//第一个状态类，继承接口
namespace DesignPatterns\Behavioral\State;
class StateCreated implements State
{
    public function proceedToNext(OrderContext $context)
    {
        $context->setState(new StateShipped());
    }
    public function toString(): string
    {
        return 'created';
    }
}


namespace DesignPatterns\Behavioral\State;
class StateShipped implements State
{
    public function proceedToNext(OrderContext $context)
    {
        $context->setState(new StateDone());
    }
    public function toString(): string
    {
        return 'shipped';
    }
}


namespace DesignPatterns\Behavioral\State;
class StateDone implements State
{
    public function proceedToNext(OrderContext $context)
    {
        // there is nothing more to do
    }
    public function toString(): string
    {
        return 'done';
    }
}


namespace DesignPatterns\Behavioral\State\Tests;
use DesignPatterns\Behavioral\State\OrderContext;
use PHPUnit\Framework\TestCase;
class StateTest extends TestCase
{
    public function testIsCreatedWithStateCreated()
    {
        $orderContext = OrderContext::create();
        $this->assertSame('created', $orderContext->toString());
    }
    public function testCanProceedToStateShipped()
    {
        $contextOrder = OrderContext::create();
        $contextOrder->proceedToNext();
        $this->assertSame('shipped', $contextOrder->toString());
    }
    public function testCanProceedToStateDone()
    {
        $contextOrder = OrderContext::create();
        $contextOrder->proceedToNext();
        $contextOrder->proceedToNext();
        $this->assertSame('done', $contextOrder->toString());
    }
    public function testStateDoneIsTheLastPossibleState()
    {
        $contextOrder = OrderContext::create();
        $contextOrder->proceedToNext();
        $contextOrder->proceedToNext();
        $contextOrder->proceedToNext();
        $this->assertSame('done', $contextOrder->toString());
    }
}
//状态模式，模式流程为对象初始化一个状态类，不同状态类都继承约束的接口，每一个状态类又可以对象状态置为下一个状态。
