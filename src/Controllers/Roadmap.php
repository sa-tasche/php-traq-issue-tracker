<?php
/*!
 * Traq
 * Copyright (C) 2009-2014 Jack Polgar
 * Copyright (C) 2012-2014 Traq.io
 * https://github.com/nirix
 * http://traq.io
 *
 * This file is part of Traq.
 *
 * Traq is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 *
 * Traq is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Traq. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Traq\Controllers;

use Traq\API;
use Traq\Helpers\Format;

/**
 * Roadmap controller.
 *
 * @author Jack P.
 * @package Traq\Controllers
 * @since 4.0
 */
class Roadmap extends AppController
{
    protected $before = ['*' => 'setTitle'];

    /**
     * Roadmap index
     *
     * @param string $filter Which milestones to display.
     */
    public function indexAction($filter = 'active')
    {
        $milestones = $this->project->milestones()
            ->orderBy('display_order', 'ASC');

        if ($filter == 'active') {
            $milestones = $milestones->where('status = ?', 1);
        }
        // Completed milestones
        elseif ($filter == 'completed') {
            $milestones = $milestones->where('status = ?', 2);
        }
        // Just the cancelled ones?
        else if ($filter == 'cancelled') {
            $milestones = $milestones->where('status = ?', 0);
        }

        $milestones = $milestones->fetchAll();
        $this->set(compact('milestones'));

        return  $this->respondTo(function($format, $controller) use ($milestones) {
            if ($format == 'html') {
                return $this->render('roadmap/index.phtml');
            } elseif ($format == 'json') {
                return API::response(200, Format::toJson($milestones));
            }
        });
    }

    /**
     * Milestone info page.
     *
     * @param string $milestone Milestone slug
     */
    public function showAction($slug)
    {
        $milestone = $this->project->milestones()
            ->where('slug = ?', $slug)->fetch();

        $this->title($milestone->name);

        return $this->render('roadmap/show.phtml', ['milestone' => $milestone]);
    }

    public function setTitle()
    {
        $this->title($this->translate('roadmap'));
    }
}
