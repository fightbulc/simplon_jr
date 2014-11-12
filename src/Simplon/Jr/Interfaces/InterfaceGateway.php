<?php

    namespace Simplon\Jr\Interfaces;

    interface InterfaceGateway
    {
        public function isEnabled();

        public function getNamespace();

        public function hasAuth();

        public function getValidServices();
    }
