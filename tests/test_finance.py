from familyhub import FinanceManager


def test_balance():
    fm = FinanceManager()
    fm.add_income(1000, "salary")
    fm.add_expense(200, "groceries")
    fm.add_expense(50, "books")
    assert fm.balance() == 750
