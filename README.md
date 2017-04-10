# Registripe Discount

Apply discounts to registrations, or ticket selections that meet specific criteria.

## Data Model

`EventTicket` has many `EventDiscount`s.
`Discount` is extended with many `DiscountConstraintsExtension`s.
