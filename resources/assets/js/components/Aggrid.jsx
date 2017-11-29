import React, {Component} from "react";
import {AgGridReact} from "ag-grid-react";
import {Router, Route, IndexRoute, browserHistory} from 'react-router';

export default class extends Component {
    constructor(props) {
        super(props);

        this.state = {
            columnDefs: this.createColumnDefs(),
            rowData: [],
            rowGroupPanelShow: "always",
            filterFileName: '',
            loadFilterFileName: '',
            columnModelFileName: '',
            loadColumnModelFileName: '',
            savedFiles: [],
            savedColumnModels: [],
        }
    }

    componentDidMount() {
        fetch('/api/aa')
            .then(response => {
                return response.json();
            })
            .then(fakedb => {
                var temp = this.createRowData(fakedb);
                this.setState({rowData: temp});
            })
        fetch('/api/loadSavedFiltersName')
            .then(response => {
                return response.json();
            })
            .then(savedName => {
                this.setState({savedFiles: savedName});
            })
        fetch('/api/loadSavedColumnModelsName')
            .then(response => {
                return response.json();
            })
            .then(savedColumnModelName => {
                this.setState({savedColumnModels: savedColumnModelName});
            })

    }

    fieldChanged(field, event) {
        this.setState({[field]: event.target.value});
        console.log(event.target.value);
    }

    saveFilterModel() {
        if(this.state.filterFileName == "")
            alert("Please add a filename");
        else {
            var temp = this.gridApi.getFilterModel();
            var tempjson = {"savedName": this.state.filterFileName};
            if (temp.sex != undefined)
                tempjson.sex = temp.sex.toString();
            else
                tempjson.sex = null;
            if (temp.country != undefined)
                tempjson.country = temp.country.toString();
            else
                tempjson.country = null;
            if (temp.profession != undefined)
                tempjson.profession = temp.profession.toString();
            else
                tempjson.profession = null;
            if (temp.proLevel != undefined)
                tempjson.proLevel = temp.proLevel.toString();
            else
                tempjson.proLevel = null;

            temp.saved = tempjson;
            if (this.gridApi.getSortModel()[0] != undefined)
                temp.sorting = this.gridApi.getSortModel()[0];
            console.log(temp);
            fetch('/api/filterSaving', {
                method: 'POST',
                body: JSON.stringify(temp)
            })
                .then(response => {
                    return response.json();
                })
                .then(filters => {
                    console.log(filters);
                    alert(filters);
                    this.setState({filterFileName: ''});
                });
            fetch('/api/loadSavedFiltersName')
                .then(response => {
                    return response.json();
                })
                .then(savedName => {
                    this.setState({savedFiles: savedName});
                })
        }
    }
    loadFilterModel(){
        if(this.state.loadFilterFileName == ""){
            alert("Please select a loadable file name");
        }else {
            var tempjson = {"savedName": this.state.loadFilterFileName}
            fetch('/api/filterLoading', {
                method: 'POST',
                body: JSON.stringify(tempjson)
            }).then(response => {
                return response.json();
            }).then(filters => {
                if (filters["sorting"] != undefined) {
                    this.gridApi.setSortModel([filters["sorting"]]);
                    delete filters.sorting;
                }
                console.log(filters);
                this.gridApi.setFilterModel(filters);
            });
        }
    }

    saveColumnModel() {
        if(this.state.columnModelFileName == "")
            alert("Please add a filename");
        else {
            var isVisible = [];
            this.columnApi.getAllColumns().forEach(function (column) {
                isVisible.push(column.isVisible());
            })
            var tempjson = {"savedName": this.state.columnModelFileName, "isVisible": isVisible.toString()};
            fetch('/api/columnModelSaving', {
                method: 'POST',
                body: JSON.stringify(tempjson)
            })
                .then(response => {
                    return response.json();
                })
                .then(columnModel => {
                    console.log(columnModel);
                    alert(columnModel);
                    this.setState({columnModelFileName: ''});
                });
            fetch('/api/loadSavedColumnModelsName')
                .then(response => {
                    return response.json();
                })
                .then(savedColumnModelName => {
                    this.setState({savedColumnModels: savedColumnModelName});
                })
        }
    }
    loadColumnModel(){
        if(this.state.loadColumnModelFileName == ""){
            alert("Please select a loadable file name");
        }else {
            var tempjson = {"savedName": this.state.loadColumnModelFileName}
            fetch('/api/columnModelLoading', {
                method: 'POST',
                body: JSON.stringify(tempjson)
            }).then(response => {
                return response.json();
            }).then(columnShoving => {
                var allColumnNames = [];
                this.columnApi.getAllColumns().forEach(function (column) {
                    allColumnNames.push(column.colDef["field"]);
                })
                for (var i = 0; i < allColumnNames.length; ++i) {
                    columnShoving[i] == "true" ? this.columnApi.setColumnVisible(allColumnNames[i], true) :
                        this.columnApi.setColumnVisible(allColumnNames[i], false);
                }
            });
        }
    }

    onGridReady(params) {
        this.gridApi = params.api;
        this.columnApi = params.columnApi;
        this.gridApi.sizeColumnsToFit();
    }

    autoSizeAll() {
        var allColumnIds = [];
        this.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        this.columnApi.autoSizeColumns(allColumnIds);
    }

    createColumnDefs() {
        return [
            {
                headerName: "Name", field: "name", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Sex", field: "sex", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }

            },
            {
                headerName: "Birthday", field: "birthday", filter: "date",
                filterParams: {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        var dateAsString = cellValue;
                        var dateParts = dateAsString.split("-");
                        var cellDate = new Date(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
                        if (filterLocalDateAtMidnight.getTime() == cellDate.getTime()) {
                            return 0;
                        }
                        if (cellDate < filterLocalDateAtMidnight) {
                            return -1;
                        }
                        if (cellDate > filterLocalDateAtMidnight) {
                            return 1;
                        }
                    }
                }
            },
            {
                headerName: "Phone number", field: "phoneNumber", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Adress", field: "adress", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Country", field: "country", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },
            {
                headerName: "Email", field: "email", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Salary", field: "salary", filter: "number"
            },
            {
                headerName: "Profession", field: "profession", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },
            {
                headerName: "Pro Level", field: "proLevel", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },

        ]
    }

    createRowData(fakedb) {
        var temp = [];
        temp.push(fakedb.map(rows => {
                return(
                    {adress: rows["Adress"], birthday:rows["Birthday"], country: rows["Country"], email:rows["Email"],
                        name:rows["Name"], phoneNumber:rows["Phone number"], profession:rows["Profession"],
                        proLevel:rows["Professional Level (1-5)"], salary:rows["Salary"], sex:rows["Sex"]}
                );
            })
        );
        return temp[0];
    }
    onFilterChanged(value) {
        this.gridApi.setQuickFilter(value);
    }

    renderSavedNames(savedFile){
        return (
            <option key = {savedFile} value = {savedFile}>  {savedFile} </option>
        );
    }

    render() {
        let containerStyle = {
            height: "60%",
            width: "100%",
        };

        return (
            <div className="container">
                <div style={containerStyle} className="ag-fresh">
                    <div>
                        <div>
                            <label>
                                Filter File Name:
                                <input type="text" id="filterfilterFileName" value={this.state.filterFileName}
                                       onChange={(event) => this.fieldChanged('filterFileName', event)}/>
                            </label>
                            <button onClick={this.saveFilterModel.bind(this)}>Save Filter Model</button>
                        </div>
                        <div>
                            <label>
                                Load Filter File from:
                                <select id="savedFiles" name="savedFiles"  required
                                        onChange={(event) => this.fieldChanged('loadFilterFileName', event)}>
                                    <option key="empty1"> </option>
                                    {this.state.savedFiles.map((savedFile) => this.renderSavedNames(savedFile))}
                                </select>
                            </label>
                            <button onClick={this.loadFilterModel.bind(this)}>Load Filter Model</button>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label>
                                Column Model File Name:
                                <input type="text" id="columnModelFileName" value={this.state.columnModelFileName}
                                       onChange={(event) => this.fieldChanged('columnModelFileName', event)}/>
                            </label>
                            <button onClick={this.saveColumnModel.bind(this)}>Save Column Model</button>
                        </div>
                        <div>
                            <label>
                                Load Column Model File From:
                                <select id="savedColumnModelFiles" name="savedColumnModelFiles"  required
                                        onChange={(event) => this.fieldChanged('loadColumnModelFileName', event)}>
                                    <option key="empty1"> </option>
                                    {this.state.savedColumnModels.map((savedColumnModelFile) => this.renderSavedNames(savedColumnModelFile))}
                                </select>
                            </label>
                            <button onClick={this.loadColumnModel.bind(this)}>Load Column Model</button>
                        </div>
                    </div>
                    <button onClick={this.autoSizeAll.bind(this)}>Auto-Size All</button>
                    <AgGridReact
                        // properties
                        columnDefs={this.state.columnDefs}
                        rowData={this.state.rowData}
                        enableColResize={true}
                        enableFilter={true}
                        enableSorting={true}
                        floatingFilter={true}
                        showToolPanel={true}
                        // events
                        onGridReady={this.onGridReady.bind(this)}
                        onFilterChanged={this.onFilterChanged.bind(this)}>

                    </AgGridReact>
                </div>
            </div>
        )
    }
};
