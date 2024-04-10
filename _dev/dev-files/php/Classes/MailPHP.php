<?php

/**
 *
 * @author Marcelo Barros marcelosrbarros@gmail.com
 * 
 */
class MailPHP {

    public $from_title = 'Markepler';
    public $from = 'contaot@markepler.com.br';
    public $to = 'marcelosrbarros@gmail.com';
    public $subject = 'Contato web.markepler.com.br';
    public $headers;
    public $message;
    public $post;
    public $dados;

    public function enviar() {
        if (!empty($this->post) && !empty($this->dados)) {
            if (in_array('', $this->dados)) {
                $sendmail = false;
            } else {
                $sendmail = mail($this->to, $this->subject, $this->formatMessage(), $this->formatHeaders());
            }
            return $sendmail;
        }
        return null;
    }

    public function setDadosPost($array = []) {
        if (empty($this->post)) {
            $this->post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
        }
        foreach ($array as $nome) {
            if ($nome == 'email') {
                $this->dados[$nome] = !empty($this->post[$nome]) && filter_var($this->post[$nome], FILTER_VALIDATE_EMAIL) ? $this->post[$nome] : "";
            } else {
                $this->dados[$nome] = !empty($this->post[$nome]) ? $this->post[$nome] : "";
            }
            $this->setDadosMessage($nome);
        }
    }

    public function setDados($array = []) {
        foreach ($array as $nome => $dados) {
            $this->post[$nome] = $dados;
            $this->setDadosPost([$nome]);
        }
    }

    public function setDadosIP() {
        $ip_addr = filter_var($_SERVER['REMOTE_ADDR'], FILTER_SANITIZE_STRIPPED);
        $date = date("d-m-Y H:i:s");
        $this->setMessage("Data: {$date}\nIP: {$ip_addr}");
    }

    public function setMessage($message) {
        $this->message .= $message . "\n";
    }

    public function setFrom($from): void {
        $this->from = $from;
    }

    public function setTo($to): void {
        $this->to = $to;
    }

    public function setSubject($subject): void {
        $this->subject = $subject;
    }

    public function setFromTitle($from_title) {
        $this->from_title = $from_title;
    }

    private function formatFrom() {
        $from = "From: {$this->from_title} <{$this->from}>";
        return $from;
    }

    private function formatMessage() {
        $message = wordwrap($this->message, 70, "\n");
        return $message;
    }

    private function setDadosMessage($nome) {
        $this->message .= ucfirst($nome) . ": {$this->dados[$nome]}\n";
    }

    private function formatHeaders() {
        $headers = "MIME-Version: 1.1\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\n";
        $headers .= $this->formatFrom();
        return $headers;
    }

}
