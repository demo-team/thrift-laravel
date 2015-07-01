<?php
namespace Demo;
/**
 * Autogenerated by Thrift Compiler (0.9.2)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;


interface AccountIf {
  /**
   * @param \Demo\AccountInfo $accountInfo
   * @return int
   * @throws \Demo\InvalideOperation
   */
  public function setUserInfo(\Demo\AccountInfo $accountInfo);
  /**
   * @param string $email
   * @return \Demo\AccountInfo
   * @throws \Demo\InvalideOperation
   */
  public function getUserInfoByEmail($email);
}

class AccountClient implements \Demo\AccountIf {
  protected $input_ = null;
  protected $output_ = null;

  protected $seqid_ = 0;

  public function __construct($input, $output=null) {
    $this->input_ = $input;
    $this->output_ = $output ? $output : $input;
  }

  public function setUserInfo(\Demo\AccountInfo $accountInfo)
  {
    $this->send_setUserInfo($accountInfo);
    return $this->recv_setUserInfo();
  }

  public function send_setUserInfo(\Demo\AccountInfo $accountInfo)
  {
    $args = new \Demo\Account_setUserInfo_args();
    $args->accountInfo = $accountInfo;
    $bin_accel = ($this->output_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($this->output_, 'setUserInfo', TMessageType::CALL, $args, $this->seqid_, $this->output_->isStrictWrite());
    }
    else
    {
      $this->output_->writeMessageBegin('setUserInfo', TMessageType::CALL, $this->seqid_);
      $args->write($this->output_);
      $this->output_->writeMessageEnd();
      $this->output_->getTransport()->flush();
    }
  }

  public function recv_setUserInfo()
  {
    $bin_accel = ($this->input_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary');
    if ($bin_accel) $result = thrift_protocol_read_binary($this->input_, '\Demo\Account_setUserInfo_result', $this->input_->isStrictRead());
    else
    {
      $rseqid = 0;
      $fname = null;
      $mtype = 0;

      $this->input_->readMessageBegin($fname, $mtype, $rseqid);
      if ($mtype == TMessageType::EXCEPTION) {
        $x = new TApplicationException();
        $x->read($this->input_);
        $this->input_->readMessageEnd();
        throw $x;
      }
      $result = new \Demo\Account_setUserInfo_result();
      $result->read($this->input_);
      $this->input_->readMessageEnd();
    }
    if ($result->success !== null) {
      return $result->success;
    }
    if ($result->ouch !== null) {
      throw $result->ouch;
    }
    throw new \Exception("setUserInfo failed: unknown result");
  }

  public function getUserInfoByEmail($email)
  {
    $this->send_getUserInfoByEmail($email);
    return $this->recv_getUserInfoByEmail();
  }

  public function send_getUserInfoByEmail($email)
  {
    $args = new \Demo\Account_getUserInfoByEmail_args();
    $args->email = $email;
    $bin_accel = ($this->output_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($this->output_, 'getUserInfoByEmail', TMessageType::CALL, $args, $this->seqid_, $this->output_->isStrictWrite());
    }
    else
    {
      $this->output_->writeMessageBegin('getUserInfoByEmail', TMessageType::CALL, $this->seqid_);
      $args->write($this->output_);
      $this->output_->writeMessageEnd();
      $this->output_->getTransport()->flush();
    }
  }

  public function recv_getUserInfoByEmail()
  {
    $bin_accel = ($this->input_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary');
    if ($bin_accel) $result = thrift_protocol_read_binary($this->input_, '\Demo\Account_getUserInfoByEmail_result', $this->input_->isStrictRead());
    else
    {
      $rseqid = 0;
      $fname = null;
      $mtype = 0;

      $this->input_->readMessageBegin($fname, $mtype, $rseqid);
      if ($mtype == TMessageType::EXCEPTION) {
        $x = new TApplicationException();
        $x->read($this->input_);
        $this->input_->readMessageEnd();
        throw $x;
      }
      $result = new \Demo\Account_getUserInfoByEmail_result();
      $result->read($this->input_);
      $this->input_->readMessageEnd();
    }
    if ($result->success !== null) {
      return $result->success;
    }
    if ($result->ouch !== null) {
      throw $result->ouch;
    }
    throw new \Exception("getUserInfoByEmail failed: unknown result");
  }

}

// HELPER FUNCTIONS AND STRUCTURES

class Account_setUserInfo_args {
  static $_TSPEC;

  /**
   * @var \Demo\AccountInfo
   */
  public $accountInfo = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'accountInfo',
          'type' => TType::STRUCT,
          'class' => '\Demo\AccountInfo',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['accountInfo'])) {
        $this->accountInfo = $vals['accountInfo'];
      }
    }
  }

  public function getName() {
    return 'Account_setUserInfo_args';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRUCT) {
            $this->accountInfo = new \Demo\AccountInfo();
            $xfer += $this->accountInfo->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Account_setUserInfo_args');
    if ($this->accountInfo !== null) {
      if (!is_object($this->accountInfo)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('accountInfo', TType::STRUCT, 1);
      $xfer += $this->accountInfo->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class Account_setUserInfo_result {
  static $_TSPEC;

  /**
   * @var int
   */
  public $success = null;
  /**
   * @var \Demo\InvalideOperation
   */
  public $ouch = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        0 => array(
          'var' => 'success',
          'type' => TType::I32,
          ),
        1 => array(
          'var' => 'ouch',
          'type' => TType::STRUCT,
          'class' => '\Demo\InvalideOperation',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['success'])) {
        $this->success = $vals['success'];
      }
      if (isset($vals['ouch'])) {
        $this->ouch = $vals['ouch'];
      }
    }
  }

  public function getName() {
    return 'Account_setUserInfo_result';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 0:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->success);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 1:
          if ($ftype == TType::STRUCT) {
            $this->ouch = new \Demo\InvalideOperation();
            $xfer += $this->ouch->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Account_setUserInfo_result');
    if ($this->success !== null) {
      $xfer += $output->writeFieldBegin('success', TType::I32, 0);
      $xfer += $output->writeI32($this->success);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->ouch !== null) {
      $xfer += $output->writeFieldBegin('ouch', TType::STRUCT, 1);
      $xfer += $this->ouch->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class Account_getUserInfoByEmail_args {
  static $_TSPEC;

  /**
   * @var string
   */
  public $email = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'email',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['email'])) {
        $this->email = $vals['email'];
      }
    }
  }

  public function getName() {
    return 'Account_getUserInfoByEmail_args';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->email);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Account_getUserInfoByEmail_args');
    if ($this->email !== null) {
      $xfer += $output->writeFieldBegin('email', TType::STRING, 1);
      $xfer += $output->writeString($this->email);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class Account_getUserInfoByEmail_result {
  static $_TSPEC;

  /**
   * @var \Demo\AccountInfo
   */
  public $success = null;
  /**
   * @var \Demo\InvalideOperation
   */
  public $ouch = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        0 => array(
          'var' => 'success',
          'type' => TType::STRUCT,
          'class' => '\Demo\AccountInfo',
          ),
        1 => array(
          'var' => 'ouch',
          'type' => TType::STRUCT,
          'class' => '\Demo\InvalideOperation',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['success'])) {
        $this->success = $vals['success'];
      }
      if (isset($vals['ouch'])) {
        $this->ouch = $vals['ouch'];
      }
    }
  }

  public function getName() {
    return 'Account_getUserInfoByEmail_result';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 0:
          if ($ftype == TType::STRUCT) {
            $this->success = new \Demo\AccountInfo();
            $xfer += $this->success->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 1:
          if ($ftype == TType::STRUCT) {
            $this->ouch = new \Demo\InvalideOperation();
            $xfer += $this->ouch->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Account_getUserInfoByEmail_result');
    if ($this->success !== null) {
      if (!is_object($this->success)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('success', TType::STRUCT, 0);
      $xfer += $this->success->write($output);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->ouch !== null) {
      $xfer += $output->writeFieldBegin('ouch', TType::STRUCT, 1);
      $xfer += $this->ouch->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class AccountProcessor {
  protected $handler_ = null;
  public function __construct($handler) {
    $this->handler_ = $handler;
  }

  public function process($input, $output) {
    $rseqid = 0;
    $fname = null;
    $mtype = 0;

    $input->readMessageBegin($fname, $mtype, $rseqid);
    $methodname = 'process_'.$fname;
    if (!method_exists($this, $methodname)) {
      $input->skip(TType::STRUCT);
      $input->readMessageEnd();
      $x = new TApplicationException('Function '.$fname.' not implemented.', TApplicationException::UNKNOWN_METHOD);
      $output->writeMessageBegin($fname, TMessageType::EXCEPTION, $rseqid);
      $x->write($output);
      $output->writeMessageEnd();
      $output->getTransport()->flush();
      return;
    }
    $this->$methodname($rseqid, $input, $output);
    return true;
  }

  protected function process_setUserInfo($seqid, $input, $output) {
    $args = new \Demo\Account_setUserInfo_args();
    $args->read($input);
    $input->readMessageEnd();
    $result = new \Demo\Account_setUserInfo_result();
    try {
      $result->success = $this->handler_->setUserInfo($args->accountInfo);
    } catch (\Demo\InvalideOperation $ouch) {
      $result->ouch = $ouch;
    }
    $bin_accel = ($output instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($output, 'setUserInfo', TMessageType::REPLY, $result, $seqid, $output->isStrictWrite());
    }
    else
    {
      $output->writeMessageBegin('setUserInfo', TMessageType::REPLY, $seqid);
      $result->write($output);
      $output->writeMessageEnd();
      $output->getTransport()->flush();
    }
  }
  protected function process_getUserInfoByEmail($seqid, $input, $output) {
    $args = new \Demo\Account_getUserInfoByEmail_args();
    $args->read($input);
    $input->readMessageEnd();
    $result = new \Demo\Account_getUserInfoByEmail_result();
    try {
      $result->success = $this->handler_->getUserInfoByEmail($args->email);
    } catch (\Demo\InvalideOperation $ouch) {
      $result->ouch = $ouch;
    }
    $bin_accel = ($output instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($output, 'getUserInfoByEmail', TMessageType::REPLY, $result, $seqid, $output->isStrictWrite());
    }
    else
    {
      $output->writeMessageBegin('getUserInfoByEmail', TMessageType::REPLY, $seqid);
      $result->write($output);
      $output->writeMessageEnd();
      $output->getTransport()->flush();
    }
  }
}

