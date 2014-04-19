<?php
namespace Pharborist;

/**
 * A lookup to a class method.
 *
 * For example, MyClass::classMethod
 */
class ClassMethodCallNode extends CallNode implements VariableExpressionNode {
  /**
   * @var Node
   */
  protected $className;

  /**
   * @var Node
   */
  protected $methodName;

  /**
   * @return Node
   */
  public function getClassName() {
    return $this->className;
  }

  /**
   * @return Node
   */
  public function getMethodName() {
    return $this->methodName;
  }
}
