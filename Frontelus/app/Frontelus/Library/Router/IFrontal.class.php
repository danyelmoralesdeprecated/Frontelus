<?php
/*                              LICENSE GPL 
 * =============================================================================
    This file is part of Frontelus.

    Frontelus is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Frontelus is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Frontelus.  If not, see <http://www.gnu.org/licenses/>.
 * =============================================================================
 */
namespace Frontelus\Library\Router;
interface IFrontal 
{
    public function init();
    public function _default();
}
