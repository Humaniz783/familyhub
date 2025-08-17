"""Simple finance manager for family budgets."""
from __future__ import annotations

from dataclasses import dataclass
from typing import List


@dataclass
class Transaction:
    amount: float
    description: str
    category: str


class FinanceManager:
    """Track family income and expenses in memory."""

    def __init__(self) -> None:
        self.transactions: List[Transaction] = []

    def add_income(self, amount: float, description: str = "", category: str = "income") -> None:
        """Record income for the family."""
        self.transactions.append(Transaction(amount, description, category))

    def add_expense(self, amount: float, description: str = "", category: str = "expense") -> None:
        """Record an expense as a negative amount."""
        self.transactions.append(Transaction(-abs(amount), description, category))

    def balance(self) -> float:
        """Return the current balance."""
        return sum(t.amount for t in self.transactions)
