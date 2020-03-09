import styled from 'styled-components'

export const TableStyles = styled.div`
  padding: 1rem;

  button {
    cursor: pointer;
    padding: .75em 1.44em;
    border: 2px solid black;
    outline: 4px solid transparent;
    background-color: transparent;

    :hover {
      outline-color: white;
      background-color: white;
    }
  }

  table {
    width: 100%;
    border-spacing: 0;
    border: 1px solid black;
    margin-bottom: 20px;

    tr {
      :last-child {
        td {
          border-bottom: 0;
        }
      }
    }

    th,
    td {
      margin: 0;
      padding: 0.5rem;
      border-bottom: 1px solid black;
      border-right: 1px solid black;

      :last-child {
        border-right: 0;
      }
    }
  }
`