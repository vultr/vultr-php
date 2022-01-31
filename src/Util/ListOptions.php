<?php

namespace Vultr\VultrPhp\Util;

class ListOptions
{
	protected int $perPage = 50;
	protected int $total = 0;

	protected string $nextCursor = '';
	protected string $prevCursor = '';
	protected string $currentCursor = '';

	public function getPerPage() : int
	{
		return $this->perPage;
	}
	public function setPerPage(int $per_page) : void
	{
		$this->perPage = $per_page;
	}

	public function getTotal() : int
	{
		return $this->total;
	}

	public function setTotal(int $total) : void
	{
		$this->total = $total;
	}

	public function getNextCursor() : string
	{
		return $this->nextCursor;
	}

	public function setNextCursor(string $next_cursor) : void
	{
		$this->nextCursor = $next_cursor;
	}

	public function getPrevCursor() : string
	{
		return $this->prevCursor;
	}

	public function setPrevCursor(string $prev_cursor) : void
	{
		$this->prevCursor = $prev_cursor;
	}

	public function getCurrentCursor() : string
	{
		return $this->currentCursor;
	}

	public function setCurrentCursor(string $current_cursor) : void
	{
		$this->currentCursor = $current_cursor;
	}
}
